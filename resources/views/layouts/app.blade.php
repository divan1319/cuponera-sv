<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'La Cuponera SV')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-ticket-perforated-fill me-2"></i>La Cuponera SV
        </a>
        @auth
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white-50 small">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm" type="submit">
                    <i class="bi bi-box-arrow-right me-1"></i>Salir
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

@auth
@if(Auth::user()->rol?->nombre === 'Empresa')
<div class="border-bottom bg-white shadow-sm">
    <div class="container">
        <nav class="nav nav-pills py-2 gap-1">
            <a class="nav-link {{ request()->routeIs('empresa.dashboard') ? 'active' : 'text-dark' }}"
               href="{{ route('empresa.dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i>Dashboard
            </a>
            @if(Auth::user()->empresa?->estado_solicitud === 'Aprobada')
            <a class="nav-link {{ request()->routeIs('empresa.ofertas.*') ? 'active' : 'text-dark' }}"
               href="{{ route('empresa.ofertas.index') }}">
                <i class="bi bi-tags me-1"></i>Mis Ofertas
            </a>
            <a class="nav-link {{ request()->routeIs('empresa.cupones.*') ? 'active' : 'text-dark' }}"
               href="{{ route('empresa.cupones.index') }}">
                <i class="bi bi-ticket-perforated me-1"></i>Cupones
            </a>
            @endif
        </nav>
    </div>
</div>
@endif
@endauth

<main class="container py-4">
    @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $tipo => $clase)
        @if(session($tipo))
        <div class="alert alert-{{ $clase }} alert-dismissible fade show" role="alert">
            {{ session($tipo) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    @endforeach

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
