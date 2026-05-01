<?php

namespace App\Http\Controllers;

use App\Models\Oferta;

class HomeController extends Controller
{
    public function __invoke()
    {
        $ofertas = Oferta::query()
            ->visiblesEnCatalogo()
            ->with(['empresa' => fn ($q) => $q->select('id_empresa', 'nombre_empresa')])
            ->withCount('cuponesComprados')
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('welcome', compact('ofertas'));
    }
}
