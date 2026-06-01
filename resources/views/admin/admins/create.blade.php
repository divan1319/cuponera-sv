@extends('layouts.app')

@section('title', 'Nuevo administrador — Admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.admins.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">← Volver al listado</a>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900">Nuevo administrador</h1>
    <p class="mt-2 text-sm text-gray-500">Crea una nueva cuenta con rol de administrador.</p>
</div>

<form method="post" action="{{ route('admin.admins.store') }}" class="max-w-2xl space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input id="name" name="name" type="text" required value="{{ old('name') }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
        <input id="email" name="email" type="email" required value="{{ old('email') }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
        <input id="password" name="password" type="password" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div class="flex flex-wrap gap-3 pt-4">
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
            Crear administrador
        </button>
        <a href="{{ route('admin.admins.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Cancelar
        </a>
    </div>
</form>
@endsection
