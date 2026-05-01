@extends('layouts.app')
@section('title', 'Dashboard de Cliente — La Cuponera SV')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Mi Panel</h1>
        <p class="mt-2 text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}. Aquí puedes ver tus cupones y ofertas disponibles.</p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Tarjeta de ejemplo -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Cupones Activos</h3>
            <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
            <a href="{{ route('cliente.cupones.index') }}" class="mt-4 inline-block text-sm font-medium text-blue-600 hover:text-blue-500">Ver mis cupones &rarr;</a>
        </div>
        
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Ofertas Disponibles</h3>
            <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
            <a href="{{ route('home') }}#ofertas" class="mt-4 inline-block text-sm font-medium text-blue-600 hover:text-blue-500">Explorar catálogo &rarr;</a>
        </div>
    </div>
</div>
@endsection