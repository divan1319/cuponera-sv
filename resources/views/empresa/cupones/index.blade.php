@extends('layouts.app')
@section('title', 'Control de Cupones — La Cuponera SV')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">
        <i class="bi bi-ticket-perforated me-2 text-primary"></i>Control de Cupones
    </h4>
    <span class="text-muted small">{{ $cupones->total() }} cupón(es) encontrado(s)</span>
</div>

{{-- Filtros --}}
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Buscar por código</label>
                <input type="text" name="buscar" class="form-control form-control-sm"
                       value="{{ request('buscar') }}" placeholder="Ej: CUP-ABC123">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Estado</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="No Canjeado" {{ request('estado') === 'No Canjeado' ? 'selected' : '' }}>
                        No Canjeado
                    </option>
                    <option value="Canjeado" {{ request('estado') === 'Canjeado' ? 'selected' : '' }}>
                        Canjeado
                    </option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                @if(request('buscar') || request('estado'))
                <a href="{{ route('empresa.cupones.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                    <i class="bi bi-x-circle me-1"></i>Limpiar
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($cupones->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-ticket-perforated display-1 text-muted"></i>
        <p class="mt-3 text-muted">No hay cupones que coincidan con los filtros.</p>
    </div>
@else
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Oferta</th>
                    <th>Precio Pagado</th>
                    <th>Estado</th>
                    <th>Fecha de Canje</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cupones as $cupon)
                <tr>
                    <td>
                        <code class="fs-6 text-dark bg-light px-2 py-1 rounded">
                            {{ $cupon->codigo_unico }}
                        </code>
                    </td>
                    <td>
                        <span class="small">{{ $cupon->oferta->titulo ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="fw-semibold">${{ number_format($cupon->precio_al_comprar, 2) }}</span>
                    </td>
                    <td>
                        @if($cupon->estado_canje === 'Canjeado')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-check-circle-fill me-1"></i>Canjeado
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-clock-fill me-1"></i>No Canjeado
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($cupon->fecha_canje)
                            <small>{{ \Carbon\Carbon::parse($cupon->fecha_canje)->format('d/m/Y H:i') }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($cupon->estado_canje === 'No Canjeado')
                            <form method="POST"
                                  action="{{ route('empresa.cupones.canjear', $cupon->id_cupon) }}"
                                  onsubmit="return confirm('¿Confirmar canje del cupón {{ $cupon->codigo_unico }}?')">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-check2-circle me-1"></i>Canjear
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">Ya canjeado</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($cupones->hasPages())
    <div class="card-footer border-0 bg-white d-flex justify-content-center">
        {{ $cupones->withQueryString()->links() }}
    </div>
    @endif
</div>
@endif

@endsection
