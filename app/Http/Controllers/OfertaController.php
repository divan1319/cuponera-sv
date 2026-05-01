<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'precio_regular' => 'required|numeric|min:0.01',
            'precio_oferta' => 'required|numeric|min:0.01|lt:precio_regular',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_canje' => 'required|date|after_or_equal:fecha_fin',
            'cantidad_limite' => 'nullable|integer|min:1',
            'descripcion' => 'required|string',
        ], [
            'precio_oferta.lt' => 'El precio de oferta debe ser menor al precio regular.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'fecha_limite_canje.after_or_equal' => 'La fecha límite de canje debe ser igual o posterior a la fecha de fin.',
        ]);

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

    public function update(Request $request, string $id)
    {
        $oferta = $this->empresaActual()->ofertas()->findOrFail($id);

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'precio_regular' => 'required|numeric|min:0.01',
            'precio_oferta' => 'required|numeric|min:0.01|lt:precio_regular',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_canje' => 'required|date|after_or_equal:fecha_fin',
            'cantidad_limite' => 'nullable|integer|min:1',
            'descripcion' => 'required|string',
            'estado' => 'required|in:Disponible,No Disponible',
        ], [
            'precio_oferta.lt' => 'El precio de oferta debe ser menor al precio regular.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'fecha_limite_canje.after_or_equal' => 'La fecha límite de canje debe ser igual o posterior a la fecha de fin.',
        ]);

        $oferta->update($data);

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
