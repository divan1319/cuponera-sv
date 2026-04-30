@extends('layouts.app')
@section('title', 'Dashboard — La Cuponera SV')

@section('content')

<div class="mb-6 flex flex-wrap items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-950">{{ $empresa->nombre_empresa }}</h1>
        <span class="mt-1 block text-sm text-slate-500">
            {{ Auth::user()->name }} · {{ $empresa->telefono }}
        </span>
    </div>

    @if($empresa->estado_solicitud === 'Pendiente')
        <span class="rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-800">
            Solicitud Pendiente
        </span>
    @elseif($empresa->estado_solicitud === 'Aprobada')
        <span class="rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
            Empresa Aprobada
        </span>
    @else
        <span class="rounded-full bg-red-100 px-3 py-1.5 text-sm font-semibold text-red-800">
            Solicitud Rechazada
        </span>
    @endif
</div>

@if($empresa->estado_solicitud === 'Pendiente')
    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm">
        Tu solicitud está siendo revisada. Una vez aprobada podrás publicar ofertas y gestionar cupones.
    </div>
@elseif($empresa->estado_solicitud === 'Rechazada')
    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
        Tu solicitud fue rechazada. Contacta al administrador para más información.
    </div>
@endif

@if($empresa->estado_solicitud === 'Aprobada')

<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto mb-3 h-2 w-10 rounded-full bg-blue-500"></div>
        <h2 class="text-3xl font-bold text-slate-950">{{ $totalOfertas }}</h2>
        <p class="mt-1 text-sm text-slate-500">Ofertas Totales</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto mb-3 h-2 w-10 rounded-full bg-emerald-500"></div>
        <h2 class="text-3xl font-bold text-slate-950">{{ $ofertasDisponibles }}</h2>
        <p class="mt-1 text-sm text-slate-500">Ofertas Disponibles</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto mb-3 h-2 w-10 rounded-full bg-sky-500"></div>
        <h2 class="text-3xl font-bold text-slate-950">{{ $cuponesVendidos }}</h2>
        <p class="mt-1 text-sm text-slate-500">Cupones Vendidos</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto mb-3 h-2 w-10 rounded-full bg-amber-500"></div>
        <h2 class="text-3xl font-bold text-slate-950">{{ $cuponesPendientes }}</h2>
        <p class="mt-1 text-sm text-slate-500">Cupones Sin Canjear</p>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <a href="{{ route('empresa.ofertas.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="rounded-2xl bg-blue-100 px-4 py-3 font-bold text-blue-700">
                OF
            </div>
            <div>
                <h2 class="font-semibold text-slate-950">Gestionar Ofertas</h2>
                <p class="text-sm text-slate-500">Crear, editar y eliminar ofertas</p>
            </div>
            <span class="ml-auto text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600">→</span>
        </div>
    </a>
    <a href="{{ route('empresa.cupones.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
        <div class="flex items-center gap-4">
            <div class="rounded-2xl bg-emerald-100 px-4 py-3 font-bold text-emerald-700">
                CP
            </div>
            <div>
                <h2 class="font-semibold text-slate-950">Control de Cupones</h2>
                <p class="text-sm text-slate-500">Verificar y canjear cupones de clientes</p>
            </div>
            <span class="ml-auto text-slate-400 transition group-hover:translate-x-1 group-hover:text-emerald-600">→</span>
        </div>
    </a>
</div>

@endif
@endsection
