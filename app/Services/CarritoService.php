<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Factura;
use App\Models\Oferta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CarritoService
{
    public const SESSION_KEY = 'carrito';

    public const LIMITE_CUPONES_POR_OFERTA_USUARIO = 5;

    /**
     * @return array<int, int> id_oferta => cantidad
     */
    public function lineas(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function cantidadTotalUnidades(): int
    {
        return (int) array_sum($this->lineas());
    }

    public function vaciar(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function establecerLinea(int $idOferta, int $cantidad, Cliente $cliente): void
    {
        if ($cantidad < 1) {
            $this->eliminarLinea($idOferta);

            return;
        }

        $oferta = $this->resolverOfertaCarrible($idOferta);
        $this->asegurarCantidadPermitida($cliente, $oferta, $cantidad);

        $carrito = $this->lineas();
        $carrito[(int) $idOferta] = $cantidad;
        Session::put(self::SESSION_KEY, $carrito);
    }

    public function agregar(int $idOferta, int $cantidad, Cliente $cliente): void
    {
        $cantidad = max(1, $cantidad);
        $actual = $this->lineas()[(int) $idOferta] ?? 0;
        $this->establecerLinea($idOferta, $actual + $cantidad, $cliente);
    }

    public function eliminarLinea(int $idOferta): void
    {
        $carrito = $this->lineas();
        unset($carrito[(int) $idOferta]);
        if ($carrito === []) {
            Session::forget(self::SESSION_KEY);
        } else {
            Session::put(self::SESSION_KEY, $carrito);
        }
    }

    public function sincronizarConReglas(Cliente $cliente): void
    {
        $carrito = $this->lineas();
        foreach ($carrito as $idOferta => $qty) {
            $oferta = Oferta::query()
                ->visiblesEnCatalogo()
                ->where('id_oferta', $idOferta)
                ->withCount('cuponesComprados')
                ->first();
            if (! $oferta) {
                $this->eliminarLinea((int) $idOferta);

                continue;
            }
            $max = $this->cantidadMaximaPermitidaEnCarrito($cliente, $oferta);
            if ((int) $qty > $max) {
                if ($max < 1) {
                    $this->eliminarLinea((int) $idOferta);
                } else {
                    $this->establecerLinea((int) $idOferta, $max, $cliente);
                }
            }
        }
    }

    public function resolverOfertaCarrible(int $idOferta): Oferta
    {
        return Oferta::query()
            ->visiblesEnCatalogo()
            ->where('id_oferta', $idOferta)
            ->withCount('cuponesComprados')
            ->firstOrFail();
    }

    public function cuponesYaCompradosPorOferta(Cliente $cliente, int $idOferta): int
    {
        return CuponComprado::query()
            ->where('id_oferta', $idOferta)
            ->whereHas('factura', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
            ->count();
    }

    public function cantidadMaximaPermitidaEnCarrito(Cliente $cliente, Oferta $oferta): int
    {
        $ya = $this->cuponesYaCompradosPorOferta($cliente, $oferta->id_oferta);
        $porReglaUsuario = max(0, self::LIMITE_CUPONES_POR_OFERTA_USUARIO - $ya);

        $vendidos = (int) $oferta->cupones_comprados_count;
        $porStock = $oferta->cantidad_limite === null
            ? PHP_INT_MAX
            : max(0, (int) $oferta->cantidad_limite - $vendidos);

        return min($porReglaUsuario, $porStock);
    }

    public function asegurarCantidadPermitida(Cliente $cliente, Oferta $oferta, int $cantidadEnCarrito): void
    {
        $max = $this->cantidadMaximaPermitidaEnCarrito($cliente, $oferta);
        if ($cantidadEnCarrito > $max) {
            throw ValidationException::withMessages([
                'cantidad' => $max === 0
                    ? 'No puedes añadir más cupones de esta oferta: alcanzaste el límite de 5 por usuario o no hay stock.'
                    : "La cantidad máxima permitida para esta oferta es {$max} cupón(es) (máx. 5 por usuario y stock disponible).",
            ]);
        }
    }

    public function procesarCheckout(Cliente $cliente, string $numeroTarjetaEnmascarado): Factura
    {
        $lineas = $this->lineas();
        if ($lineas === []) {
            throw ValidationException::withMessages([
                'carrito' => 'Tu carrito está vacío.',
            ]);
        }

        $this->sincronizarConReglas($cliente);
        $lineas = $this->lineas();
        if ($lineas === []) {
            throw ValidationException::withMessages([
                'carrito' => 'Tu carrito quedó vacío tras actualizar cantidades según stock y límites.',
            ]);
        }

        return DB::transaction(function () use ($lineas, $cliente, $numeroTarjetaEnmascarado) {
            $total = 0.0;
            $detalle = [];

            foreach ($lineas as $idOferta => $qty) {
                $oferta = Oferta::query()
                    ->visiblesEnCatalogo()
                    ->where('id_oferta', $idOferta)
                    ->lockForUpdate()
                    ->withCount('cuponesComprados')
                    ->firstOrFail();

                $this->asegurarCantidadPermitida($cliente, $oferta, $qty);

                $precio = (float) $oferta->precio_oferta;
                $total += $precio * $qty;
                $detalle[] = ['oferta' => $oferta, 'qty' => $qty];
            }

            $factura = Factura::create([
                'id_cliente' => $cliente->id_cliente,
                'total_pagado' => $total,
                'metodo_pago' => 'Tarjeta (Simulada)',
                'numero_tarjeta' => $numeroTarjetaEnmascarado,
            ]);

            foreach ($detalle as $row) {
                $oferta = $row['oferta'];
                $qty = $row['qty'];
                $precio = (float) $oferta->precio_oferta;

                for ($i = 0; $i < $qty; $i++) {
                    CuponComprado::create([
                        'id_factura' => $factura->id_factura,
                        'id_oferta' => $oferta->id_oferta,
                        'codigo_unico' => $this->nuevoCodigoCupon(),
                        'precio_al_comprar' => $precio,
                        'estado_canje' => 'No Canjeado',
                    ]);
                }
            }

            $this->vaciar();

            return $factura;
        });
    }

    protected function nuevoCodigoCupon(): string
    {
        do {
            $code = 'CPN-'.strtoupper(bin2hex(random_bytes(8)));
        } while (CuponComprado::where('codigo_unico', $code)->exists());

        return $code;
    }
}
