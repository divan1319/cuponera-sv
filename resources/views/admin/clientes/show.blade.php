@extends('layouts.app')

@section('title', 'Cliente — Admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.clientes.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">← Volver al listado</a>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900">
        {{ trim($cliente->nombres.' '.$cliente->apellidos) }}
    </h1>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Datos de cuenta</h2>
        <dl class="mt-6 space-y-4 text-sm">
            <div>
                <dt class="font-medium text-gray-500">Correo</dt>
                <dd class="mt-1 text-gray-900">{{ $cliente->user?->email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">DUI</dt>
                <dd class="mt-1 text-gray-900">{{ $cliente->dui }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Fecha de nacimiento</dt>
                <dd class="mt-1 text-gray-900">{{ $cliente->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Registro en la plataforma</dt>
                <dd class="mt-1 text-gray-900">
                    {{ $cliente->user?->created_at ? $cliente->user->created_at->format('d/m/Y H:i') : '—' }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Actividad de cupones</h2>
        <ul class="mt-6 divide-y divide-gray-100 rounded-xl border border-gray-100 bg-gray-50/50">
            <li class="flex justify-between px-4 py-3 text-sm">
                <span class="text-gray-600">Compras realizadas</span>
                <span class="font-bold text-gray-900">{{ $comprasCount }}</span>
            </li>
            <li class="flex justify-between px-4 py-3 text-sm">
                <span class="text-gray-600">Cupones canjeados</span>
                <span class="font-bold text-emerald-700">{{ $canjeados }}</span>
            </li>
            <li class="flex justify-between px-4 py-3 text-sm">
                <span class="text-gray-600">Cupones no canjeados</span>
                <span class="font-bold text-amber-800">{{ $noCanjeados }}</span>
            </li>
        </ul>

        @if($tieneCuponesComprados)
            <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                Esta cuenta tiene cupones comprados; no puede eliminarse desde el administrador.
            </div>
        @else
            <div class="mt-6 flex flex-wrap gap-3">
                <form method="post" action="{{ route('admin.clientes.destroy', $cliente->id_cliente) }}" class="inline"
                      onsubmit="return confirm('¿Eliminar definitivamente este cliente y su cuenta?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                        Eliminar cuenta
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
