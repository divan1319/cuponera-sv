@extends('layouts.app')

@section('title', 'Editar empresa — Admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.empresas.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">← Volver al listado</a>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900">Editar empresa</h1>
    <p class="mt-2 text-sm text-gray-500">{{ $empresa->nombre_empresa }} · Estado: {{ $empresa->estado_solicitud }}</p>

    @if($ventasCount > 0)
        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            Esta empresa tiene <strong>{{ $ventasCount }}</strong> ventas registradas y no puede ser eliminada.
        </div>
    @endif
</div>

<form method="post" action="{{ route('admin.empresas.update', $empresa->id_empresa) }}" class="max-w-2xl space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label for="nombre_empresa" class="block text-sm font-medium text-gray-700">Nombre de la empresa</label>
        <input id="nombre_empresa" name="nombre_empresa" type="text" required value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('nombre_empresa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nit" class="block text-sm font-medium text-gray-700">NIT</label>
        <input id="nit" name="nit" type="text" required value="{{ old('nit', $empresa->nit) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('nit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3" required class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('direccion', $empresa->direccion) }}</textarea>
        @error('direccion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input id="telefono" name="telefono" type="text" required value="{{ old('telefono', $empresa->telefono) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('telefono')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    @if($empresa->estado_solicitud === 'Aprobada')
        <div>
            <label for="porcentaje_comision" class="block text-sm font-medium text-gray-700">Comisión (%)</label>
            <input id="porcentaje_comision" name="porcentaje_comision" type="number" step="0.01" min="0" max="100" required
                   value="{{ old('porcentaje_comision', $empresa->porcentaje_comision) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:max-w-xs">
            @error('porcentaje_comision')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    @endif

    <div class="flex flex-wrap gap-3 pt-4">
        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
            Guardar cambios
        </button>
        <a href="{{ route('admin.empresas.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Cancelar
        </a>
    </div>
</form>
@endsection
