<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'La Cuponera SV')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased font-sans">

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a class="text-xl font-bold tracking-tighter text-gray-900 transition hover:text-blue-600" href="/">
            La Cuponera <span class="text-blue-600">SV</span>
        </a>
        @auth
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-600">
                {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm font-medium text-gray-500 transition hover:text-red-600" type="submit">
                    Cerrar sesión
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

@auth
@if(Auth::user()->rol?->nombre === 'Empresa')
<div class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex gap-6 py-3 overflow-x-auto">
            <a class="text-sm font-medium transition {{ request()->routeIs('empresa.dashboard') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('empresa.dashboard') }}">
                Dashboard
            </a>
            @if(Auth::user()->empresa?->estado_solicitud === 'Aprobada')
            <a class="text-sm font-medium transition {{ request()->routeIs('empresa.ofertas.*') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('empresa.ofertas.index') }}">
                Mis Ofertas
            </a>
            <a class="text-sm font-medium transition {{ request()->routeIs('empresa.cupones.*') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('empresa.cupones.index') }}">
                Cupones
            </a>
            @endif
        </nav>
    </div>
</div>
@endif
@if(Auth::user()->rol?->nombre === 'Admin')
<div class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex gap-6 py-3 overflow-x-auto">
            <a class="text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>
            <a class="text-sm font-medium transition {{ request()->routeIs('admin.solicitudes') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('admin.solicitudes') }}">
                Solicitudes
            </a>
            <a class="text-sm font-medium transition {{ request()->routeIs('admin.reportes') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
               href="{{ route('admin.reportes') }}">
                Reportes
            </a>
        </nav>
    </div>
</div>
@endif
@if(Auth::user()->rol?->nombre === 'Cliente')
<div class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex gap-6 py-3 overflow-x-auto">
            <a class="text-sm font-medium transition {{ request()->routeIs('home') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
                href="{{ route('home') }}">
                 Inicio
             </a>
            <a class="text-sm font-medium transition {{ request()->routeIs('cliente.dashboard') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : 'text-gray-500 hover:text-gray-900' }}"
                href="{{ route('cliente.dashboard') }}">
                 Dashboard
             </a>
        </nav>
    </div>
</div>
@endif
@endauth

<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @php
        $alertas = [
            'success' => 'bg-green-50 text-green-800 border-green-200',
            'error' => 'bg-red-50 text-red-800 border-red-200',
            'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            'info' => 'bg-blue-50 text-blue-800 border-blue-200',
        ];
    @endphp

    @foreach($alertas as $tipo => $clase)
        @if(session($tipo))
        <div class="mb-6 rounded-lg border px-4 py-3 text-sm {{ $clase }}" role="alert">
            {{ session($tipo) }}
        </div>
        @endif
    @endforeach

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
