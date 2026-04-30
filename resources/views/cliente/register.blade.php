@extends('layouts.app')
@section('title', 'Registro de Cliente — La Cuponera SV')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $errorInputClass = 'border-red-400 focus:border-red-500 focus:ring-red-100';
@endphp

<div class="mx-auto max-w-3xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-blue-700 px-6 py-5 text-white">
            <h1 class="text-xl font-semibold">Registro de Cliente</h1>
            <p class="mt-1 text-sm text-blue-100">Crea tu cuenta para comprar cupones en el catálogo.</p>
        </div>
        <div class="p-6">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('cliente.register.store') }}">
                @csrf

                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Cuenta de acceso</p>
                <div class="mb-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Correo electrónico <span class="text-red-600">*</span></label>
                        <input type="email" name="email" class="{{ $inputClass }} @error('email') {{ $errorInputClass }} @enderror" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Contraseña <span class="text-red-600">*</span></label>
                        <input type="password" name="password" class="{{ $inputClass }}" required minlength="8" autocomplete="new-password">
                        <p class="mt-1 text-sm text-slate-500">Mínimo 8 caracteres.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Confirmar contraseña <span class="text-red-600">*</span></label>
                        <input type="password" name="password_confirmation" class="{{ $inputClass }}" required autocomplete="new-password">
                    </div>
                </div>

                <div class="my-6 border-t border-slate-200"></div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Datos personales</p>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nombres <span class="text-red-600">*</span></label>
                        <input type="text" name="nombres" class="{{ $inputClass }} @error('nombres') {{ $errorInputClass }} @enderror" value="{{ old('nombres') }}" maxlength="100" required>
                        @error('nombres')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Apellidos <span class="text-red-600">*</span></label>
                        <input type="text" name="apellidos" class="{{ $inputClass }} @error('apellidos') {{ $errorInputClass }} @enderror" value="{{ old('apellidos') }}" maxlength="100" required>
                        @error('apellidos')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">DUI <span class="text-red-600">*</span></label>
                        <input type="text" name="dui" class="{{ $inputClass }} @error('dui') {{ $errorInputClass }} @enderror" value="{{ old('dui') }}" placeholder="00000000-0" maxlength="10" required>
                        @error('dui')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Fecha de nacimiento <span class="text-red-600">*</span></label>
                        <input type="date" name="fecha_nacimiento" class="{{ $inputClass }} @error('fecha_nacimiento') {{ $errorInputClass }} @enderror" value="{{ old('fecha_nacimiento') }}" required>
                        @error('fecha_nacimiento')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Crear cuenta
                </button>
            </form>

            <div class="my-5 border-t border-slate-200"></div>
            <p class="text-center text-sm text-slate-500">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection
