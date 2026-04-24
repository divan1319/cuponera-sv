@extends('layouts.app')
@section('title', 'Dashboard — La Cuponera SV')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-1 fw-bold">{{ $empresa->nombre_empresa }}</h4>
        <span class="text-muted small">
            <i class="bi bi-person me-1"></i>{{ Auth::user()->name }}
            &nbsp;·&nbsp;
            <i class="bi bi-telephone me-1"></i>{{ $empresa->telefono }}
        </span>
    </div>

    @if($empresa->estado_solicitud === 'Pendiente')
        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
            <i class="bi bi-hourglass-split me-1"></i>Solicitud Pendiente
        </span>
    @elseif($empresa->estado_solicitud === 'Aprobada')
        <span class="badge bg-success fs-6 px-3 py-2">
            <i class="bi bi-check-circle-fill me-1"></i>Empresa Aprobada
        </span>
    @else
        <span class="badge bg-danger fs-6 px-3 py-2">
            <i class="bi bi-x-circle-fill me-1"></i>Solicitud Rechazada
        </span>
    @endif
</div>

@if($empresa->estado_solicitud === 'Pendiente')
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="bi bi-info-circle-fill me-2"></i>
        Tu solicitud está siendo revisada. Una vez aprobada podrás publicar ofertas y gestionar cupones.
    </div>
@elseif($empresa->estado_solicitud === 'Rechazada')
    <div class="alert alert-danger border-0 shadow-sm">
        <i class="bi bi-x-octagon-fill me-2"></i>
        Tu solicitud fue rechazada. Contacta al administrador para más información.
    </div>
@endif

@if($empresa->estado_solicitud === 'Aprobada')

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-4">
                <i class="bi bi-tags-fill display-5 text-primary mb-2"></i>
                <h2 class="mb-0 fw-bold">{{ $totalOfertas }}</h2>
                <p class="text-muted small mb-0">Ofertas Totales</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-4">
                <i class="bi bi-check-circle-fill display-5 text-success mb-2"></i>
                <h2 class="mb-0 fw-bold">{{ $ofertasDisponibles }}</h2>
                <p class="text-muted small mb-0">Ofertas Disponibles</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-4">
                <i class="bi bi-ticket-perforated-fill display-5 text-info mb-2"></i>
                <h2 class="mb-0 fw-bold">{{ $cuponesVendidos }}</h2>
                <p class="text-muted small mb-0">Cupones Vendidos</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-4">
                <i class="bi bi-clock-history display-5 text-warning mb-2"></i>
                <h2 class="mb-0 fw-bold">{{ $cuponesPendientes }}</h2>
                <p class="text-muted small mb-0">Cupones Sin Canjear</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <a href="{{ route('empresa.ofertas.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-tags-fill fs-4 text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-dark">Gestionar Ofertas</h6>
                    <small class="text-muted">Crear, editar y eliminar ofertas</small>
                </div>
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('empresa.cupones.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-ticket-perforated-fill fs-4 text-success"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-dark">Control de Cupones</h6>
                    <small class="text-muted">Verificar y canjear cupones de clientes</small>
                </div>
                <i class="bi bi-chevron-right ms-auto text-muted"></i>
            </div>
        </a>
    </div>
</div>

@endif
@endsection
