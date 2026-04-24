@extends('layouts.app')
@section('title', 'Mis Ofertas — La Cuponera SV')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-tags me-2 text-primary"></i>Mis Ofertas</h4>
    <a href="{{ route('empresa.ofertas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nueva Oferta
    </a>
</div>

@if($ofertas->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-tags display-1 text-muted"></i>
        <p class="mt-3 text-muted">Aún no tienes ofertas publicadas.</p>
        <a href="{{ route('empresa.ofertas.create') }}" class="btn btn-primary mt-1">
            <i class="bi bi-plus-circle me-2"></i>Crear primera oferta
        </a>
    </div>
@else
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Oferta</th>
                    <th>Precio Regular</th>
                    <th>Precio Oferta</th>
                    <th>Vigencia</th>
                    <th>Límite Canje</th>
                    <th class="text-center">Cupones</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ofertas as $oferta)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $oferta->titulo }}</div>
                        @if($oferta->cantidad_limite)
                            <small class="text-muted">
                                <i class="bi bi-boxes me-1"></i>Límite: {{ $oferta->cantidad_limite }} unid.
                            </small>
                        @endif
                    </td>
                    <td class="text-decoration-line-through text-muted">
                        ${{ number_format($oferta->precio_regular, 2) }}
                    </td>
                    <td class="fw-bold text-success">
                        ${{ number_format($oferta->precio_oferta, 2) }}
                    </td>
                    <td>
                        <small class="d-block">{{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}</small>
                        <small class="text-muted">al {{ \Carbon\Carbon::parse($oferta->fecha_fin)->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($oferta->fecha_limite_canje)->format('d/m/Y') }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $oferta->cupones_count }}</span>
                    </td>
                    <td>
                        @if($oferta->estado === 'Disponible')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Disponible
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>No Disponible
                            </span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('empresa.ofertas.edit', $oferta->id_oferta) }}"
                           class="btn btn-sm btn-outline-primary me-1" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST"
                              action="{{ route('empresa.ofertas.destroy', $oferta->id_oferta) }}"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar la oferta «{{ $oferta->titulo }}»?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
