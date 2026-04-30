@extends('layouts.app')
@section('title', 'Registro de Cliente — La Cuponera SV')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500';
    $errorInputClass = 'border-red-400 focus:border-red-500 focus:ring-red-500';
@endphp

<div class="mx-auto mt-8 max-w-2xl">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Crea tu cuenta</h1>
        <p class="mt-2 text-sm text-gray-500">Regístrate para comprar los mejores cupones del catálogo</p>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-100">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cliente.register.store') }}">
            @csrf

            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Cuenta de acceso</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="email" class="{{ $inputClass }} @error('email') {{ $errorInputClass }} @enderror" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password" class="{{ $inputClass }}" required minlength="8" autocomplete="new-password">
                        <p class="mt-1.5 text-xs text-gray-500">Mínimo 8 caracteres.</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="{{ $inputClass }}" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Datos personales</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nombres</label>
                        <input type="text" name="nombres" class="{{ $inputClass }} @error('nombres') {{ $errorInputClass }} @enderror" value="{{ old('nombres') }}" maxlength="100" required>
                        @error('nombres')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Apellidos</label>
                        <input type="text" name="apellidos" class="{{ $inputClass }} @error('apellidos') {{ $errorInputClass }} @enderror" value="{{ old('apellidos') }}" maxlength="100" required>
                        @error('apellidos')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">DUI</label>
                        <input type="text" name="dui" class="{{ $inputClass }} @error('dui') {{ $errorInputClass }} @enderror" value="{{ old('dui') }}" placeholder="00000000-0" maxlength="10" required>
                        @error('dui')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="{{ $inputClass }} @error('fecha_nacimiento') {{ $errorInputClass }} @enderror" value="{{ old('fecha_nacimiento') }}" required>
                        @error('fecha_nacimiento')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Crear cuenta
            </button>
        </form>

        <div class="mt-8 border-t border-gray-100 pt-6">
            <p class="text-center text-sm text-gray-500">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection
