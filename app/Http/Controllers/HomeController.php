<?php

namespace App\Http\Controllers;

use App\Models\Oferta;

class HomeController extends Controller
{
    public function __invoke()
    {
        $ofertas = Oferta::query()
            ->where('estado', 'Disponible')
            ->whereHas('empresa', fn ($q) => $q->where('estado_solicitud', 'Aprobada'))
            ->where('fecha_inicio', '<=', now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', now());
            })
            ->with(['empresa' => fn ($q) => $q->select('id_empresa', 'nombre_empresa')])
            ->withCount('cuponesComprados')
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('welcome', compact('ofertas'));
    }
}
