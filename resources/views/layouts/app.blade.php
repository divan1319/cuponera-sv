<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'La Cuponera SV')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

<nav class="bg-blue-700 text-white shadow-sm">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a class="text-lg font-bold tracking-tight" href="/">
            La Cuponera SV
        </a>
        @auth
        <div class="flex items-center gap-3">
            <span class="text-sm text-blue-100">
                {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg border border-white/40 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-white hover:text-blue-700" type="submit">
                    Salir
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

@auth
@if(Auth::user()->rol?->nombre === 'Empresa')
<div class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap gap-2 py-3">
            <a class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('empresa.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
               href="{{ route('empresa.dashboard') }}">
                Dashboard
            </a>
            @if(Auth::user()->empresa?->estado_solicitud === 'Aprobada')
            <a class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('empresa.ofertas.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
               href="{{ route('empresa.ofertas.index') }}">
                Mis Ofertas
            </a>
            <a class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('empresa.cupones.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
               href="{{ route('empresa.cupones.index') }}">
                Cupones
            </a>
            @endif
        </nav>
    </div>
</div>
@endif
@if(Auth::user()->rol?->nombre === 'Admin')
<div class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap gap-2 py-3">
            <a class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.solicitudes') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
               href="{{ route('admin.solicitudes') }}">
                Solicitudes
            </a>
            <a class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.reportes') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
               href="{{ route('admin.reportes') }}">
                Reportes
            </a>
        </nav>
    </div>
</div>
@endif

@endauth

<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    @php
        $alertas = [
            'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'error' => 'border-red-200 bg-red-50 text-red-800',
            'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
            'info' => 'border-sky-200 bg-sky-50 text-sky-800',
        ];
    @endphp

    @foreach($alertas as $tipo => $clase)
        @if(session($tipo))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm font-medium {{ $clase }}" role="alert">
            {{ session($tipo) }}
        </div>
        @endif
    @endforeach

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
