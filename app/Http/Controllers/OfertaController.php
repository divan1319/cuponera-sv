<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfertaRequest;
use App\Http\Requests\UpdateOfertaRequest;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfertaController extends Controller
{
    private function empresaActual()
    {
        return Auth::user()->empresa;
    }

    public function index()
    {
        $ofertas = $this->empresaActual()
            ->ofertas()
            ->orderByDesc('fecha_creacion')
            ->withCount('cuponesComprados')
            ->get();

        return view('empresa.ofertas.index', compact('ofertas'));
    }

    public function create()
    {
        return view('empresa.ofertas.create');
    }

    public function store(StoreOfertaRequest $request)
    {
        $data = $request->validated();

        $data['id_empresa'] = $this->empresaActual()->id_empresa;
        $data['estado'] = 'Disponible';

        Oferta::create($data);

        return redirect()->route('empresa.ofertas.index')
            ->with('success', 'Oferta creada exitosamente.');
    }

    public function edit(string $id)
    {
        $oferta = $this->empresaActual()->ofertas()->findOrFail($id);

        return view('empresa.ofertas.edit', compact('oferta'));
    }

    public function update(UpdateOfertaRequest $request, string $id)
    {
        $oferta = $this->empresaActual()->ofertas()->findOrFail($id);

        $oferta->update($request->validated());

        return redirect()->route('empresa.ofertas.index')
            ->with('success', 'Oferta actualizada exitosamente.');
    }

    public function destroy(Request $request, string $id)
    {
        $oferta = Oferta::query()->findOrFail($id);

        if ($oferta->cuponesComprados()->exists()) {
            return back()->with('error', 'No se puede eliminar una oferta con cupones vendidos.');
        }

        $oferta->delete();

        return redirect()->route('empresa.ofertas.index')
            ->with('success', 'Oferta eliminada exitosamente.');
    }
}
