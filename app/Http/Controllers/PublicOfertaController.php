<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Services\CarritoService;
use Illuminate\Support\Facades\Auth;

class PublicOfertaController extends Controller
{
    public function show(string $id, CarritoService $carrito)
    {
        $oferta = Oferta::query()
            ->visiblesEnCatalogo()
            ->where('id_oferta', $id)
            ->with([
                'empresa' => fn ($q) => $q->select(
                    'id_empresa',
                    'nombre_empresa',
                    'direccion',
                    'telefono'
                ),
            ])
            ->withCount('cuponesComprados')
            ->firstOrFail();

        $maxComprable = null;
        $user = Auth::user();
        if ($user && $user->rol?->nombre === 'Cliente' && $user->cliente) {
            $maxComprable = $carrito->cantidadMaximaPermitidaEnCarrito($user->cliente, $oferta);
        }

        return view('ofertas.show', compact('oferta', 'maxComprable'));
    }
}
