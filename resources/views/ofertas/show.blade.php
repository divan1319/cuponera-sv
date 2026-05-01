@extends('layouts.app')

@section('title', $oferta->titulo . ' — La Cuponera SV')

@php
    $vendidos = (int) $oferta->cupones_comprados_count;
    $limite = $oferta->cantidad_limite;
    $restantes = $limite !== null ? max(0, (int) $limite - $vendidos) : null;
    $agotado = $limite !== null && $restantes === 0;
    $descuento = $oferta->precio_regular > 0
        ? (int) round(100 - ($oferta->precio_oferta / $oferta->precio_regular) * 100)
        : null;
@endphp

@section('content')
<div class="mx-auto max-w-3xl px-4 pb-16 sm:px-0">
    <a href="{{ route('home') }}#ofertas"
       class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 transition hover:text-blue-800">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Volver a ofertas
    </a>

    <header class="mt-6 overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm ring-1 ring-black/5">
        @if($descuento !== null && $descuento > 0)
            <div class="flex justify-end bg-gradient-to-r from-slate-50 to-blue-50/80 px-4 py-2">
                <span class="rounded-lg bg-blue-600 px-2.5 py-1 text-xs font-bold text-white shadow-sm">-{{ $descuento }}%</span>
            </div>
        @endif
        <div class="p-6 sm:p-8">
            @if($oferta->empresa)
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ $oferta->empresa->nombre_empresa }}</p>
            @endif
            <h1 class="mt-2 text-2xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-3xl">
                {{ $oferta->titulo }}
            </h1>
            <div class="mt-6 flex flex-wrap items-baseline gap-3">
                <span class="text-lg text-gray-400 line-through">${{ number_format($oferta->precio_regular, 2) }}</span>
                <span class="text-3xl font-extrabold tracking-tight text-emerald-600">${{ number_format($oferta->precio_oferta, 2) }}</span>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-gray-100 pt-6">
                @if($agotado)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-800 ring-1 ring-inset ring-red-200/80">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        Agotados
                    </span>
                @elseif($restantes !== null)
                    <span class="text-sm font-medium text-gray-700">
                        Quedan <span class="font-bold tabular-nums text-gray-900">{{ $restantes }}</span> {{ $restantes === 1 ? 'cupón' : 'cupones' }}
                    </span>
                @else
                    <span class="text-sm font-semibold text-emerald-700">Cupones disponibles</span>
                    <span class="text-xs text-gray-500">(sin límite de unidades)</span>
                @endif
            </div>
        </div>
    </header>

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm ring-1 ring-black/5">
        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Comprar cupones</h2>
        <p class="mt-2 text-sm text-gray-600">Máximo 5 cupones por oferta.</p>

        @guest
            <p class="mt-4 text-sm text-gray-700">
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800">Inicia sesión</a>
                con una cuenta cliente para añadir al carrito.
                ¿No tienes cuenta?
                <a href="{{ route('cliente.register') }}" class="font-semibold text-blue-600 hover:text-blue-800">Regístrate</a>.
            </p>
        @else
            @if(Auth::user()->rol?->nombre !== 'Cliente')
                <p class="mt-4 text-sm text-amber-800">Solo las cuentas con rol <strong>Cliente</strong> pueden comprar cupones en la plataforma.</p>
            @elseif($agotado)
                <p class="mt-4 text-sm text-red-700">Esta oferta está agotada; no se pueden añadir más cupones.</p>
            @elseif(($maxComprable ?? 0) < 1)
                <p class="mt-4 text-sm text-amber-800">Ya alcanzaste el máximo de cupones para esta oferta (5 en total por usuario) o no hay cupones disponibles.</p>
            @else
                <form action="{{ route('cliente.carrito.store') }}" method="POST" class="mt-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
                    @csrf
                    <input type="hidden" name="id_oferta" value="{{ $oferta->id_oferta }}">
                    <div>
                        <label for="cantidad-oferta" class="mb-1 block text-sm font-medium text-gray-700">Cantidad</label>
                        <select name="cantidad" id="cantidad-oferta" class="rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @for($i = 1; $i <= $maxComprable; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Puedes añadir hasta {{ $maxComprable }} ahora.</p>
                    </div>
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Añadir al carrito
                    </button>
                </form>
                @error('cantidad')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endif
        @endguest
    </div>

    <div class="mt-8 grid gap-6 sm:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Vigencia de la oferta</h2>
            <ul class="mt-3 space-y-2 text-sm text-gray-700">
                <li><span class="font-medium text-gray-900">Inicio:</span> {{ $oferta->fecha_inicio->format('d/m/Y H:i') }}</li>
                <li><span class="font-medium text-gray-900">Fin:</span> {{ $oferta->fecha_fin ? $oferta->fecha_fin->format('d/m/Y H:i') : '—' }}</li>
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Canje</h2>
            <p class="mt-3 text-sm text-gray-700">
                <span class="font-medium text-gray-900">Límite para canjear:</span>
                {{ $oferta->fecha_limite_canje->format('d/m/Y') }}
            </p>
            @if($oferta->cantidad_limite)
                <p class="mt-2 text-sm text-gray-700">
                    <span class="font-medium text-gray-900">Límite de cupones:</span> {{ $oferta->cantidad_limite }} unidades
                </p>
            @endif
        </div>
    </div>

    @if($oferta->empresa && ($oferta->empresa->direccion || $oferta->empresa->telefono))
        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Negocio</h2>
            <ul class="mt-3 space-y-1 text-sm text-gray-700">
                @if($oferta->empresa->direccion)
                    <li>{{ $oferta->empresa->direccion }}</li>
                @endif
                @if($oferta->empresa->telefono)
                    <li><span class="font-medium text-gray-900">Tel:</span> {{ $oferta->empresa->telefono }}</li>
                @endif
            </ul>
        </div>
    @endif

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Descripción</h2>
        <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700">
            {{ $oferta->descripcion }}
        </div>
    </div>
</div>
@endsection
