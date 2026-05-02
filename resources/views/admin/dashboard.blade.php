@extends('layouts.app')

@section('title', 'Dashboard Administrador — La Cuponera SV')

@section('content')
@php
    $nombresMes = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
    $chartLabels = collect($porDia)->keys()->map(function ($fecha) {
        return \Carbon\Carbon::parse($fecha)->format('j');
    })->values()->all();
    $chartValues = collect($porDia)->values()->map(fn ($v) => round($v, 2))->all();
    $tituloMes = $nombresMes[(int) $month] ?? $month;
    $graficoLegend = 'Comisiones — '.$tituloMes.' '.$year;
@endphp

<div class="mb-10">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-500">
        Ganancias de la plataforma (comisiones) por mes y últimas compras.
    </p>
</div>

<div class="mb-10 flex flex-wrap items-end justify-between gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
    <form method="get" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label for="filtro-mes" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Mes</label>
            <select id="filtro-mes" name="month"
                    class="min-w-[10rem] rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($nombresMes as $num => $nombre)
                    <option value="{{ $num }}" @selected((int)$month === (int)$num)>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filtro-year" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Año</label>
            <select id="filtro-year" name="year"
                    class="min-w-[8rem] rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($yearChoices as $y)
                    <option value="{{ $y }}" @selected((int)$year === (int)$y)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            Ver período
        </button>
    </form>

    <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-right">
        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800">{{ $tituloMes }} {{ $year }}</p>
        <p class="text-xs text-emerald-700">Total ganancias (comisiones)</p>
        <p class="text-2xl font-bold text-emerald-900">${{ number_format($totalMes, 2) }}</p>
    </div>
</div>

<div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Ganancias día a día</h2>
        <p class="mt-1 text-sm text-gray-500">Distribución de comisiones cobradas en el mes seleccionado.</p>
        <div class="mt-6 h-72 relative">
            <canvas id="chartGanancias" aria-label="Gráfico de ganancias diarias"></canvas>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900">Últimas 10 compras</h2>
        <p class="mt-1 text-sm text-gray-500">Facturas registradas más recientemente.</p>

        @if($ultimasCompras->isEmpty())
            <div class="mt-8 rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                Aún no hay compras registradas.
            </div>
        @else
            <ul class="mt-6 divide-y divide-gray-100 overflow-hidden rounded-xl border border-gray-100">
                @foreach($ultimasCompras as $fac)
                    @php
                        $cantCupones = $fac->cuponesComprados->count();
                        $nombreCliente = trim(($fac->cliente->nombres ?? '').' '.($fac->cliente->apellidos ?? ''));
                    @endphp
                    <li class="flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">
                                Factura #{{ $fac->id_factura }}
                                <span class="font-normal text-gray-500"> · {{ $nombreCliente }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($fac->fecha_compra)->format('d/m/Y H:i') }}
                                · {{ $cantCupones }} {{ $cantCupones === 1 ? 'cupón' : 'cupones' }}
                                · {{ $fac->metodo_pago }}
                            </p>
                        </div>
                        <p class="shrink-0 text-lg font-bold text-blue-700">${{ number_format($fac->total_pagado, 2) }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('chartGanancias');
    if (!canvas || typeof Chart === 'undefined') return;

    const labels = @json($chartLabels).map(function (d) { return 'Día ' + d; });
    const data = @json($chartValues);
    const legend = @json($graficoLegend);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: legend,
                data: data,
                backgroundColor: 'rgba(37, 99, 235, 0.45)',
                borderColor: 'rgb(37, 99, 235)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        autoSkip: true,
                        maxRotation: 0,
                        font: { size: 11 }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return '$' + Number(value).toLocaleString('es-SV', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                        }
                    }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const v = ctx.parsed.y;
                            return ' Comisiones: $' + Number(v).toLocaleString('es-SV', { minimumFractionDigits: 2 });
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
