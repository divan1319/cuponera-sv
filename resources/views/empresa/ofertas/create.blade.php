@extends('layouts.app')
@section('title', 'Nueva Oferta — La Cuponera SV')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('empresa.ofertas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Nueva Oferta</h4>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.ofertas.store') }}">
            @csrf
            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Título de la oferta <span class="text-danger">*</span></label>
                    <input type="text" name="titulo"
                           class="form-control @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo') }}" required>
                    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Precio Regular ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_regular" step="0.01" min="0.01"
                               class="form-control @error('precio_regular') is-invalid @enderror"
                               value="{{ old('precio_regular') }}" required>
                        @error('precio_regular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Precio de Oferta ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_oferta" step="0.01" min="0.01"
                               class="form-control @error('precio_oferta') is-invalid @enderror"
                               value="{{ old('precio_oferta') }}" required>
                        @error('precio_oferta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-text">Debe ser menor al precio regular.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha y Hora de Inicio <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="fecha_inicio"
                           class="form-control @error('fecha_inicio') is-invalid @enderror"
                           value="{{ old('fecha_inicio') }}" required>
                    @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha y Hora de Fin <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="fecha_fin"
                           class="form-control @error('fecha_fin') is-invalid @enderror"
                           value="{{ old('fecha_fin') }}" required>
                    @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha Límite de Canje <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_limite_canje"
                           class="form-control @error('fecha_limite_canje') is-invalid @enderror"
                           value="{{ old('fecha_limite_canje') }}" required>
                    @error('fecha_limite_canje')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cantidad Límite</label>
                    <input type="number" name="cantidad_limite" min="1"
                           class="form-control @error('cantidad_limite') is-invalid @enderror"
                           value="{{ old('cantidad_limite') }}" placeholder="Sin límite">
                    <div class="form-text">Opcional. Deja vacío para sin límite.</div>
                    @error('cantidad_limite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción <span class="text-danger">*</span></label>
                    <textarea name="descripcion" rows="4"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              placeholder="Describe los detalles, condiciones y beneficios de la oferta..."
                              required>{{ old('descripcion') }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Guardar Oferta
                </button>
                <a href="{{ route('empresa.ofertas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection
