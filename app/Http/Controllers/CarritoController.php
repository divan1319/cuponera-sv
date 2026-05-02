<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Services\CarritoService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarritoController extends Controller
{
    public function __construct(
        protected CarritoService $carrito
    ) {}

    public function index(Request $request)
    {
        $cliente = $request->user()->cliente;
        abort_unless($cliente, 403);

        $this->carrito->sincronizarConReglas($cliente);

        $lineas = $this->carrito->lineas();
        $ids = array_keys($lineas);

        $ofertas = $ids === []
            ? collect()
            : Oferta::query()
                ->visiblesEnCatalogo()
                ->whereIn('id_oferta', $ids)
                ->with(['empresa' => fn ($q) => $q->select('id_empresa', 'nombre_empresa')])
                ->withCount('cuponesComprados')
                ->get()
                ->keyBy('id_oferta');

        $items = [];
        $total = 0.0;
        foreach ($lineas as $idOferta => $qty) {
            $oferta = $ofertas->get($idOferta);
            if (! $oferta) {
                $this->carrito->eliminarLinea((int) $idOferta);

                continue;
            }
            $max = $this->carrito->cantidadMaximaPermitidaEnCarrito($cliente, $oferta);
            $subtotal = (float) $oferta->precio_oferta * $qty;
            $total += $subtotal;
            $items[] = [
                'oferta' => $oferta,
                'cantidad' => $qty,
                'max_permitido' => $max,
                'subtotal' => $subtotal,
            ];
        }

        return view('cliente.carrito.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $cliente = $request->user()->cliente;
        abort_unless($cliente, 403);

        $data = $request->validate([
            'id_oferta' => ['required', 'integer', 'exists:ofertas,id_oferta'],
            'cantidad' => ['sometimes', 'integer', 'min:1', 'max:5'],
        ]);
        $cantidad = (int) ($data['cantidad'] ?? 1);

        if (! Oferta::query()->visiblesEnCatalogo()->where('id_oferta', $data['id_oferta'])->exists()) {
            return back()->with('error', 'Esta oferta no está disponible.');
        }

        $this->carrito->agregar((int) $data['id_oferta'], $cantidad, $cliente);

        return back()->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request, string $id)
    {
        $cliente = $request->user()->cliente;
        abort_unless($cliente, 403);

        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0', 'max:5'],
        ]);

        $this->carrito->establecerLinea((int) $id, (int) $data['cantidad'], $cliente);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(Request $request, string $id)
    {
        abort_unless($request->user()->cliente, 403);
        $this->carrito->eliminarLinea((int) $id);

        return back()->with('success', 'Item eliminado del carrito.');
    }

    public function checkout(Request $request)
    {
        $cliente = $request->user()->cliente;
        abort_unless($cliente, 403);

        $data = $request->validate([
            'nombre_titular' => ['required', 'string', 'max:120'],
            'numero_tarjeta' => ['required', 'string'],
            'vencimiento' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\s*\/\s*\d{2}$/'],
            'cvv' => ['required', 'string', 'regex:/^\d{3,4}$/'],
        ]);

        $digitos = preg_replace('/\D/', '', $data['numero_tarjeta']);
        if (strlen($digitos) < 13 || strlen($digitos) > 19) {
            throw ValidationException::withMessages([
                'numero_tarjeta' => 'El número de tarjeta debe tener entre 13 y 19 dígitos.',
            ]);
        }

        $enmascarado = '**** '.substr($digitos, -4);

        $factura = $this->carrito->procesarCheckout($cliente, $enmascarado);

        return redirect()
            ->route('cliente.carrito.index')
            ->with('success', "Compra realizada. Revisa tus cupones.");
    }
}
