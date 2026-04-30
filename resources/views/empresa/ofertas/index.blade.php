@extends('layouts.app')
@section('title', 'Mis Ofertas — La Cuponera SV')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold text-slate-950">Mis Ofertas</h1>
    <a href="{{ route('empresa.ofertas.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
        Nueva Oferta
    </a>
</div>

@if($ofertas->isEmpty())
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-slate-500">Aún no tienes ofertas publicadas.</p>
        <a href="{{ route('empresa.ofertas.create') }}" class="mt-4 inline-flex rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            Crear primera oferta
        </a>
    </div>
@else
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Oferta</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Precio Regular</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Precio Oferta</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Vigencia</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Límite Canje</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-600">Cupones</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Estado</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($ofertas as $oferta)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-slate-950">{{ $oferta->titulo }}</div>
                        @if($oferta->cantidad_limite)
                            <p class="mt-1 text-xs text-slate-500">Límite: {{ $oferta->cantidad_limite }} unid.</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500 line-through">
                        ${{ number_format($oferta->precio_regular, 2) }}
                    </td>
                    <td class="px-4 py-3 font-bold text-emerald-700">
                        ${{ number_format($oferta->precio_oferta, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="block text-slate-700">{{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}</span>
                        <span class="text-xs text-slate-500">al {{ \Carbon\Carbon::parse($oferta->fecha_fin)->format('d/m/Y') }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-700">
                        {{ \Carbon\Carbon::parse($oferta->fecha_limite_canje)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">{{ $oferta->cupones_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($oferta->estado === 'Disponible')
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                Disponible
                            </span>
                        @else
                            <span class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                No Disponible
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('empresa.ofertas.edit', $oferta->id_oferta) }}"
                           class="inline-flex rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50" title="Editar">
                            Editar
                        </a>
                        <form method="POST"
                              action="{{ route('empresa.ofertas.destroy', $oferta->id_oferta) }}"
                              class="inline"
                              onsubmit="return confirm('¿Eliminar la oferta «{{ $oferta->titulo }}»?')">
                            @csrf
                            @method('DELETE')
                            <button class="ml-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50" title="Eliminar">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
