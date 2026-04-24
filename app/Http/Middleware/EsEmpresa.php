<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsEmpresa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->rol?->nombre !== 'Empresa') {
            abort(403, 'Acceso restringido a empresas.');
        }

        return $next($request);
    }
}
