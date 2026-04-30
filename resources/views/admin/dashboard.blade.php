@extends('layouts.app')

@section('title', 'Dashboard Administrador — La Cuponera SV')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard de Administración</h1>
    <p class="mt-2 text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}.</p>
</div>

<div class="grid gap-6 sm:grid-cols-2">
    <a href="{{ route('admin.solicitudes') }}" class="group relative rounded-2xl bg-white p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md hover:border-blue-100">
        <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Solicitudes de Empresas</h2>
        <p class="mt-1 text-sm text-gray-500">Revisa y aprueba o rechaza nuevas empresas.</p>
    </a>
    <a href="{{ route('admin.reportes') }}" class="group relative rounded-2xl bg-white p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md hover:border-blue-100">
        <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Reportes</h2>
        <p class="mt-1 text-sm text-gray-500">Visualiza estadísticas y reportes de la plataforma.</p>
    </a>
</div>
@endsection