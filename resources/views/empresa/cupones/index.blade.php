@extends('layouts.app')
@section('title', 'Control de Cupones — La Cuponera SV')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold text-slate-950">Control de Cupones</h1>
    <span class="text-sm text-slate-500">{{ $cupones->total() }} cupón(es) encontrado(s)</span>
</div>

{{-- Filtros --}}
<div class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-3 md:grid-cols-12 md:items-end">
            <div class="md:col-span-4">
                <label class="mb-1 block text-sm font-medium text-slate-700">Buscar por código</label>
                <input type="text" name="buscar" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                       value="{{ request('buscar') }}" placeholder="Ej: CPN-0000000000000000">
            </div>
            <div class="md:col-span-3">
                <label class="mb-1 block text-sm font-medium text-slate-700">Estado</label>
                <select name="estado" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    <option value="">Todos los estados</option>
                    <option value="No Canjeado" {{ request('estado') === 'No Canjeado' ? 'selected' : '' }}>
                        No Canjeado
                    </option>
                    <option value="Canjeado" {{ request('estado') === 'Canjeado' ? 'selected' : '' }}>
                        Canjeado
                    </option>
                </select>
            </div>
            <div class="flex flex-wrap gap-2 md:col-span-5">
                <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Filtrar
                </button>
                @if(request('buscar') || request('estado'))
                <a href="{{ route('empresa.cupones.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Limpiar
                </a>
                @endif
            </div>
        </form>
</div>

@if($cupones->isEmpty())
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-slate-500">No hay cupones que coincidan con los filtros.</p>
    </div>
@else
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Código</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Oferta</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Precio Pagado</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Estado</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Fecha de Canje</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($cupones as $cupon)
                <tr class="transition hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <code class="rounded-lg bg-slate-100 px-2 py-1 text-sm font-semibold text-slate-800">
                            {{ $cupon->codigo_unico }}
                        </code>
                    </td>
                    <td class="px-4 py-3 text-slate-700">
                        {{ $cupon->oferta->titulo ?? '—' }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-950">
                        ${{ number_format($cupon->precio_al_comprar, 2) }}
                    </td>
                    <td class="px-4 py-3">
                        @if($cupon->estado_canje === 'Canjeado')
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                Canjeado
                            </span>
                        @else
                            <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                No Canjeado
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-700">
                        @if($cupon->fecha_canje)
                            {{ \Carbon\Carbon::parse($cupon->fecha_canje)->format('d/m/Y H:i') }}
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($cupon->estado_canje === 'No Canjeado')
                            <form method="POST"
                                  action="{{ route('empresa.cupones.canjear', $cupon->id_cupon) }}"
                                  onsubmit="return confirm('¿Confirmar canje del cupón {{ $cupon->codigo_unico }}?')">
                                @csrf
                                @method('PATCH')
                                <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                    Canjear
                                </button>
                            </form>
                        @else
                            <span class="text-sm text-slate-500">Ya canjeado</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($cupones->hasPages())
    <div class="border-t border-slate-200 bg-white px-4 py-3">
        {{ $cupones->withQueryString()->links() }}
    </div>
    @endif
</div>
@endif

@endsection
