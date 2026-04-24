<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpresaAprobada
{
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa || $empresa->estado_solicitud !== 'Aprobada') {
            return redirect()->route('empresa.dashboard')
                ->with('warning', 'Tu empresa aún no ha sido aprobada por el administrador.');
        }

        return $next($request);
    }
}
