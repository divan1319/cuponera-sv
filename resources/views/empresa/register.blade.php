@extends('layouts.app')
@section('title', 'Registro de Empresa — La Cuponera SV')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $errorInputClass = 'border-red-400 focus:border-red-500 focus:ring-red-100';
@endphp

<div class="mx-auto max-w-3xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-blue-700 px-6 py-5 text-white">
            <h1 class="text-xl font-semibold">Registro de Empresa</h1>
            <p class="mt-1 text-sm text-blue-100">Tu solicitud será revisada y aprobada por un administrador.</p>
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

            <form method="POST" action="{{ route('empresa.register.store') }}">
                @csrf

                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Datos del representante</p>
                <div class="mb-6 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nombre completo <span class="text-red-600">*</span></label>
                        <input type="text" name="name" class="{{ $inputClass }} @error('name') {{ $errorInputClass }} @enderror" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Correo electrónico <span class="text-red-600">*</span></label>
                        <input type="email" name="email" class="{{ $inputClass }} @error('email') {{ $errorInputClass }} @enderror" value="{{ old('email') }}" required>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Contraseña <span class="text-red-600">*</span></label>
                        <input type="password" name="password" class="{{ $inputClass }}" required minlength="8">
                        <p class="mt-1 text-sm text-slate-500">Mínimo 8 caracteres.</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Confirmar contraseña <span class="text-red-600">*</span></label>
                        <input type="password" name="password_confirmation" class="{{ $inputClass }}" required>
                    </div>
                </div>

                <div class="my-6 border-t border-slate-200"></div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Datos de la empresa</p>
                <div class="grid gap-4 md:grid-cols-12">
                    <div class="md:col-span-8">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nombre de la empresa <span class="text-red-600">*</span></label>
                        <input type="text" name="nombre_empresa" class="{{ $inputClass }} @error('nombre_empresa') {{ $errorInputClass }} @enderror" value="{{ old('nombre_empresa') }}" required>
                        @error('nombre_empresa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">NIT <span class="text-red-600">*</span></label>
                        <input type="text" name="nit" class="{{ $inputClass }} @error('nit') {{ $errorInputClass }} @enderror" value="{{ old('nit') }}" placeholder="0000-000000-000-0" required>
                        @error('nit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-8">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Dirección <span class="text-red-600">*</span></label>
                        <input type="text" name="direccion" class="{{ $inputClass }} @error('direccion') {{ $errorInputClass }} @enderror" value="{{ old('direccion') }}" required>
                        @error('direccion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Teléfono <span class="text-red-600">*</span></label>
                        <input type="text" name="telefono" class="{{ $inputClass }} @error('telefono') {{ $errorInputClass }} @enderror" value="{{ old('telefono') }}" placeholder="2222-3333" required>
                        @error('telefono')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="mt-6 w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                    Enviar Solicitud de Registro
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
