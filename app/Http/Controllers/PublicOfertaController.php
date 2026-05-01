<?php

namespace App\Http\Controllers;

use App\Models\Oferta;

class PublicOfertaController extends Controller
{
    public function show(string $id)
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

        return view('ofertas.show', compact('oferta'));
    }
}
