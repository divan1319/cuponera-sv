@extends('layouts.app')
@section('title', 'Dashboard — La Cuponera SV')

@section('content')

<div class="mb-8 flex flex-wrap items-start justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $empresa->nombre_empresa }}</h1>
        <span class="mt-1.5 block text-sm text-gray-500">
            {{ Auth::user()->name }} &middot; {{ $empresa->telefono }}
        </span>
    </div>

    @if($empresa->estado_solicitud === 'Pendiente')
        <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-sm font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
            Solicitud Pendiente
        </span>
    @elseif($empresa->estado_solicitud === 'Aprobada')
        <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
            Empresa Aprobada
        </span>
    @else
        <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
            Solicitud Rechazada
        </span>
    @endif
</div>

@if($empresa->estado_solicitud === 'Pendiente')
    <div class="rounded-xl bg-yellow-50 p-4 text-sm text-yellow-800 border border-yellow-100 mb-8">
        Tu solicitud está siendo revisada. Una vez aprobada podrás publicar ofertas y gestionar cupones.
    </div>
@elseif($empresa->estado_solicitud === 'Rechazada')
    <div class="rounded-xl bg-red-50 p-4 text-sm text-red-800 border border-red-100 mb-8">
        Tu solicitud fue rechazada. Contacta al administrador para más información.
    </div>
@endif

@if($empresa->estado_solicitud === 'Aprobada')

<div class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
        <p class="text-sm font-medium text-gray-500">Ofertas Totales</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $totalOfertas }}</h2>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
        <p class="text-sm font-medium text-gray-500">Ofertas Disponibles</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $ofertasDisponibles }}</h2>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
        <p class="text-sm font-medium text-gray-500">Cupones Vendidos</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $cuponesVendidos }}</h2>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
        <p class="text-sm font-medium text-gray-500">Cupones Sin Canjear</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ $cuponesPendientes }}</h2>
    </div>
</div>

<div class="grid gap-5 md:grid-cols-2">
    <a href="{{ route('empresa.ofertas.index') }}" class="group relative rounded-2xl bg-white p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md hover:border-blue-100">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Gestionar Ofertas</h2>
                <p class="text-sm text-gray-500">Crear, editar y eliminar ofertas</p>
            </div>
        </div>
    </a>
    <a href="{{ route('empresa.cupones.index') }}" class="group relative rounded-2xl bg-white p-6 shadow-sm border border-gray-100 transition-all hover:shadow-md hover:border-green-100">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Control de Cupones</h2>
                <p class="text-sm text-gray-500">Verificar y canjear cupones de clientes</p>
            </div>
        </div>
    </a>
</div>

@endif
@endsection
