@extends('layouts.app')
@section('title', 'Iniciar Sesión — La Cuponera SV')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <i class="bi bi-ticket-perforated-fill display-4 text-primary"></i>
            <h3 class="mt-2 fw-bold">La Cuponera SV</h3>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">Iniciar Sesión</h5>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Recordarme</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                    </button>
                </form>

                <hr class="my-3">
                <p class="text-center text-muted small mb-0">
                    ¿Eres empresa y no tienes cuenta?
                    <a href="{{ route('empresa.register') }}" class="text-decoration-none">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
