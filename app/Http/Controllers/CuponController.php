<?php

namespace App\Http\Controllers;

use App\Models\CuponComprado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponController extends Controller
{
    public function index(Request $request)
    {
        $empresa   = Auth::user()->empresa;
        $ofertaIds = $empresa->ofertas()->pluck('id_oferta');

        $cupones = CuponComprado::whereIn('id_oferta', $ofertaIds)
            ->with('oferta')
            ->when($request->estado, fn ($q, $e) => $q->where('estado_canje', $e))
            ->when($request->buscar, fn ($q, $b) => $q->where('codigo_unico', 'ilike', "%{$b}%"))
            ->orderByDesc('id_cupon')
            ->paginate(20);

        return view('empresa.cupones.index', compact('cupones'));
    }

    public function canjear(string $id)
    {
        $empresa   = Auth::user()->empresa;
        $ofertaIds = $empresa->ofertas()->pluck('id_oferta');

        $cupon = CuponComprado::whereIn('id_oferta', $ofertaIds)
            ->where('id_cupon', $id)
            ->firstOrFail();

        if ($cupon->estado_canje === 'Canjeado') {
            return back()->with('error', 'Este cupón ya fue canjeado anteriormente.');
        }

        $cupon->update([
            'estado_canje' => 'Canjeado',
            'fecha_canje'  => now(),
        ]);

        return back()->with('success', "Cupón {$cupon->codigo_unico} canjeado exitosamente.");
    }
}
