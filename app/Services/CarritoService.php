<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Factura;
use App\Models\Oferta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

/**
 * Gestiona el carrito de compras en sesión: líneas por oferta, límites por usuario/stock,
 * sincronización con reglas del catálogo y checkout transaccional.
 */
class CarritoService
{
    /** Clave usada en la sesión Laravel para persistir `{ id_oferta => cantidad }`. */
    public const SESSION_KEY = 'carrito';

    /** Máximo de cupones de una misma oferta que puede poseer un usuario (comprados + carrito). */
    public const LIMITE_CUPONES_POR_OFERTA_USUARIO = 5;

    /**
     * Devuelve las líneas del carrito desde la sesión.
     *
     * @return array<int, int> Mapa id_oferta => cantidad
     */
    public function lineas(): array
    {
        // Lectura desde sesión: si no existe la clave, el carrito se trata como vacío ([]).
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Suma las cantidades de todas las líneas del carrito.
     */
    public function cantidadTotalUnidades(): int
    {
        // Suma los valores del mapa (cantidades por oferta); el cast fuerza entero estable.
        return (int) array_sum($this->lineas());
    }

    /**
     * Elimina todas las líneas del carrito de la sesión.
     */
    public function vaciar(): void
    {
        // Elimina la entrada de sesión por completo (no deja [] explícito).
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Fija la cantidad de una oferta en el carrito. Si la cantidad es menor que 1, elimina la línea.
     * Valida oferta disponible en catálogo y límites (usuario/stock).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la oferta no está en catálogo
     * @throws ValidationException Si la cantidad supera lo permitido
     */
    public function establecerLinea(int $idOferta, int $cantidad, Cliente $cliente): void
    {
        // Cantidad inválida: equivalente a quitar la oferta del carrito.
        if ($cantidad < 1) {
            $this->eliminarLinea($idOferta);

            return;
        }

        // Oferta debe existir y estar visible para comprar; valida también límite 5/usuario + stock.
        $oferta = $this->resolverOfertaCarrible($idOferta);
        $this->asegurarCantidadPermitida($cliente, $oferta, $cantidad);

        // Copia mutable del snapshot en sesión, actualiza esa línea y persiste todo el mapa.
        $carrito = $this->lineas();
        $carrito[(int) $idOferta] = $cantidad;
        Session::put(self::SESSION_KEY, $carrito);
    }

    /**
     * Incrementa la cantidad de una oferta (mínimo 1 por llamada).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Propagado por `resolverOfertaCarrible`
     * @throws ValidationException Si el total sobrepasa el máximo permitido
     */
    public function agregar(int $idOferta, int $cantidad, Cliente $cliente): void
    {
        // Evita sumar “0 o negativo”: al menos una unidad por operación de agregar.
        $cantidad = max(1, $cantidad);
        // Lee lo que ya hay en carrito para esta oferta (0 si es línea nueva).
        $actual = $this->lineas()[(int) $idOferta] ?? 0;
        // Una sola validación/consistencia vía establecerLinea sobre el total deseado.
        $this->establecerLinea($idOferta, $actual + $cantidad, $cliente);
    }

    /**
     * Quita una oferta del carrito. Si el carrito queda vacío, borra también la clave de sesión.
     */
    public function eliminarLinea(int $idOferta): void
    {
        $carrito = $this->lineas();
        unset($carrito[(int) $idOferta]);
        // Sin líneas restantes conviene borrar la clave (mismo comportamiento que vaciar parcialmente).
        if ($carrito === []) {
            Session::forget(self::SESSION_KEY);
        } else {
            Session::put(self::SESSION_KEY, $carrito);
        }
    }

    /**
     * Recorre el carrito y ajusta cantidades: elimina ofertas ya no visibles en catálogo
     * o recorta al máximo permitido por cliente y stock.
     */
    public function sincronizarConReglas(Cliente $cliente): void
    {
        $carrito = $this->lineas();
        foreach ($carrito as $idOferta => $qty) {
            // Revalidar que la oferta siga disponible en catálogo; con vendidos para calcular stock.
            $oferta = Oferta::query()
                ->visiblesEnCatalogo()
                ->where('id_oferta', $idOferta)
                ->withCount('cuponesComprados')
                ->first();
            if (! $oferta) {
                // Si desapareció o dejó de ser “comprable”, la línea no tiene sentido.
                $this->eliminarLinea((int) $idOferta);

                continue;
            }
            // Tope combinado: regla por usuario + cupos restantes si hay límite de oferta.
            $max = $this->cantidadMaximaPermitidaEnCarrito($cliente, $oferta);
            if ((int) $qty > $max) {
                // Nada permitido: quitar línea para no cobrar algo inválido.
                if ($max < 1) {
                    $this->eliminarLinea((int) $idOferta);
                } else {
                    // Recorta al máximo válido manteniendo la línea en carrito.
                    $this->establecerLinea((int) $idOferta, $max, $cliente);
                }
            }
        }
    }

    /**
     * Obtiene una oferta que puede añadirse al carrito (visible en catálogo, con conteo de cupones vendidos).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si la oferta no existe o no aplica
     */
    public function resolverOfertaCarrible(int $idOferta): Oferta
    {
        // Misma regla que el catálogo de compra; firstOrFail evita seguir con IDs inválidos.
        return Oferta::query()
            ->visiblesEnCatalogo()
            ->where('id_oferta', $idOferta)
            ->withCount('cuponesComprados')
            ->firstOrFail();
    }

    /**
     * Cuenta cupones ya pagados por el cliente para una oferta (vía factura asociada).
     */
    public function cuponesYaCompradosPorOferta(Cliente $cliente, int $idOferta): int
    {
        // Solo cupones efectivamente ligados al cliente a través de su factura.
        return CuponComprado::query()
            ->where('id_oferta', $idOferta)
            ->whereHas('factura', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
            ->count();
    }

    /**
     * Límite de unidades que el cliente puede tener en carrito para esta oferta:
     * el menor entre (5 menos ya comprados) y stock disponible según `cantidad_limite`.
     *
     * @param  Oferta  $oferta  Debe incluir la relación contada como `cupones_comprados_count` cuando aplique.
     */
    public function cantidadMaximaPermitidaEnCarrito(Cliente $cliente, Oferta $oferta): int
    {
        // Hueco restante bajo la regla fija por usuario/oferta (incluye lo ya pagado histórico).
        $ya = $this->cuponesYaCompradosPorOferta($cliente, $oferta->id_oferta);
        $porReglaUsuario = max(0, self::LIMITE_CUPONES_POR_OFERTA_USUARIO - $ya);

        // Stock lógico: límite de oferta menos cupones ya vendidos (conteo en el modelo cargado).
        $vendidos = (int) $oferta->cupones_comprados_count;
        $porStock = $oferta->cantidad_limite === null
            ? PHP_INT_MAX
            : max(0, (int) $oferta->cantidad_limite - $vendidos);

        // El carrito no puede sobrepasar el cuello de botella más restrictivo.
        return min($porReglaUsuario, $porStock);
    }

    /**
     * Comprueba que la cantidad deseada no excede el máximo; si lo hace, lanza error de validación.
     *
     * @throws ValidationException
     */
    public function asegurarCantidadPermitida(Cliente $cliente, Oferta $oferta, int $cantidadEnCarrito): void
    {
        $max = $this->cantidadMaximaPermitidaEnCarrito($cliente, $oferta);
        if ($cantidadEnCarrito > $max) {
            // Mensaje distinto cuando no queda ninguna unidad disponible vs. cuando hay pero menos de las pedidas.
            throw ValidationException::withMessages([
                'cantidad' => $max === 0
                    ? 'No puedes añadir más cupones de esta oferta: alcanzaste el límite de 5 por usuario o no hay stock.'
                    : "La cantidad máxima permitida para esta oferta es {$max} cupón(es) (máx. 5 por usuario y stock disponible).",
            ]);
        }
    }

    /**
     * Cierra la compra: sincroniza reglas, valida líneas bloqueando ofertas, crea factura y cupones
     * uno por unidad y vacía el carrito dentro de una transacción.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si una línea ya no existe en catálogo
     * @throws ValidationException Si el carrito queda vacío o alguna línea viola límites
     */
    public function procesarCheckout(Cliente $cliente, string $numeroTarjetaEnmascarado): Factura
    {
        // Checkout temprano: no hay trabajo que hacer sin líneas persistidas en sesión.
        $lineas = $this->lineas();
        if ($lineas === []) {
            throw ValidationException::withMessages([
                'carrito' => 'Tu carrito está vacío.',
            ]);
        }

        // Alinear carrito con catálogo y reglas antes de cobrar (puede borrar/recortar líneas).
        $this->sincronizarConReglas($cliente);
        $lineas = $this->lineas();
        if ($lineas === []) {
            throw ValidationException::withMessages([
                'carrito' => 'Tu carrito quedó vacío tras actualizar cantidades según stock y límites.',
            ]);
        }

        // Todo lo que cree factura/cupones o falle debe revertirse junto (atomicidad).
        return DB::transaction(function () use ($lineas, $cliente, $numeroTarjetaEnmascarado) {
            $total = 0.0;
            // Guardamos oferta + cantidad porque tras el primer bucle necesitamos generar cupones por unidad.
            $detalle = [];

            foreach ($lineas as $idOferta => $qty) {
                // Lock evita dos compras concurrentes sobre el mismo stock en esta ventana corta.
                $oferta = Oferta::query()
                    ->visiblesEnCatalogo()
                    ->where('id_oferta', $idOferta)
                    ->lockForUpdate()
                    ->withCount('cuponesComprados')
                    ->firstOrFail();

                // Última comprobación con datos frescos dentro de la misma transacción.
                $this->asegurarCantidadPermitida($cliente, $oferta, $qty);

                $precio = (float) $oferta->precio_oferta;
                $total += $precio * $qty;
                $detalle[] = ['oferta' => $oferta, 'qty' => $qty];
            }

            // Un único cobro/registro fiscal simulado; el detalle granular va en CuponComprado.
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

                // Cada cupón es un código propio unido a esta factura/oferta/precio congelado.
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

            // Compra aplicada en BD: liberar sesión para no repetir líneas fantasmas.
            $this->vaciar();

            return $factura;
        });
    }

    /**
     * Genera un código único tipo `CPN-XXXXXXXX...` garantizando que no exista en `CuponesComprado`.
     */
    protected function nuevoCodigoCupon(): string
    {
        do {
            // Prefijo lectura humana + 16 hex alto entropía; reintenta si hay colisión rara en BD.
            $code = 'CPN-'.strtoupper(bin2hex(random_bytes(8)));
        } while (CuponComprado::where('codigo_unico', $code)->exists());

        return $code;
    }
}
