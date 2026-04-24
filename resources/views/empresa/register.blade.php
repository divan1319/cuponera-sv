@extends('layouts.app')
@section('title', 'Registro de Empresa — La Cuponera SV')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Registro de Empresa</h5>
                <small class="opacity-75">Tu solicitud será revisada y aprobada por un administrador.</small>
            </div>
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

                <form method="POST" action="{{ route('empresa.register.store') }}">
                    @csrf

                    <p class="text-muted small text-uppercase fw-semibold mb-3">Datos del representante</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <div class="form-text">Mínimo 8 caracteres.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted small text-uppercase fw-semibold mb-3 mt-3">Datos de la empresa</p>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_empresa"
                                   class="form-control @error('nombre_empresa') is-invalid @enderror"
                                   value="{{ old('nombre_empresa') }}" required>
                            @error('nombre_empresa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NIT <span class="text-danger">*</span></label>
                            <input type="text" name="nit"
                                   class="form-control @error('nit') is-invalid @enderror"
                                   value="{{ old('nit') }}" placeholder="0000-000000-000-0" required>
                            @error('nit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Dirección <span class="text-danger">*</span></label>
                            <input type="text" name="direccion"
                                   class="form-control @error('direccion') is-invalid @enderror"
                                   value="{{ old('direccion') }}" required>
                            @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="telefono"
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono') }}" placeholder="2222-3333" required>
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i>Enviar Solicitud de Registro
                        </button>
                    </div>
                </form>

                <hr class="my-3">
                <p class="text-center text-muted small mb-0">
                    ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
