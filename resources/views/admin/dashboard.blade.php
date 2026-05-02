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
                    class="min-w-40 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($nombresMes as $num => $nombre)
                    <option value="{{ $num }}" @selected((int)$month === (int)$num)>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filtro-year" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Año</label>
            <select id="filtro-year" name="year"
                    class="min-w-32 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

<div class="grid grid-cols-1 gap-6 sm:gap-8 lg:grid-cols-2 lg:gap-10 lg:items-start">
    <section class="min-w-0 overflow-hidden rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5 md:p-6">
        <h2 class="text-base font-semibold text-gray-900 sm:text-lg">Ganancias día a día</h2>
        <p class="mt-1 text-xs text-gray-500 sm:text-sm">Distribución de comisiones cobradas en el mes seleccionado.</p>
        {{-- Contenedor único para el canvas (Chart.js mide el padre): sin overflow-scroll que robe el ancho al redimensionar grid/flex --}}
        <div class="relative mt-4 w-full min-w-0 sm:mt-6">
            <div class="relative isolate mx-auto h-52 min-h-48 w-full min-w-0 sm:h-64 sm:min-h-60 md:h-72">
                <canvas id="chartGanancias" class="block h-full w-full max-w-full min-w-0" aria-label="Gráfico de ganancias diarias"></canvas>
            </div>
        </div>
    </section>

    <section class="min-w-0 overflow-hidden rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5 md:p-6">
        <h2 class="text-base font-semibold text-gray-900 sm:text-lg">Últimas 10 compras</h2>
        <p class="mt-1 text-xs text-gray-500 sm:text-sm">Facturas registradas más recientemente.</p>

        @if($ultimasCompras->isEmpty())
            <div class="mt-6 rounded-lg border border-dashed border-gray-200 bg-gray-50 px-3 py-6 text-center text-sm text-gray-500 sm:mt-8 sm:px-4 sm:py-8">
                Aún no hay compras registradas.
            </div>
        @else
            <ul class="mt-4 divide-y divide-gray-100 overflow-hidden rounded-xl border border-gray-100 sm:mt-6">
                @foreach($ultimasCompras as $fac)
                    @php
                        $cantCupones = $fac->cuponesComprados->count();
                        $nombreCliente = trim(($fac->cliente->nombres ?? '').' '.($fac->cliente->apellidos ?? ''));
                    @endphp
                    <li class="flex flex-col gap-2 px-3 py-3.5 sm:flex-row sm:items-start sm:justify-between sm:px-4 sm:py-4">
                        <div class="min-w-0 flex-1">
                            <p class="break-words font-semibold text-gray-900">
                                Factura #{{ $fac->id_factura }}
                                <span class="font-normal text-gray-500"> · {{ $nombreCliente }}</span>
                            </p>
                            <p class="mt-1 text-xs leading-relaxed text-gray-500">
                                <span>{{ \Carbon\Carbon::parse($fac->fecha_compra)->format('d/m/Y H:i') }}</span>
                                <span class="mx-1 text-gray-300" aria-hidden="true">·</span>
                                <span>{{ $cantCupones }} {{ $cantCupones === 1 ? 'cupón' : 'cupones' }}</span>
                                <span class="mx-1 text-gray-300" aria-hidden="true">·</span>
                                <span class="break-words">{{ $fac->metodo_pago }}</span>
                            </p>
                        </div>
                        <p class="shrink-0 border-t border-gray-100 pt-3 text-lg font-bold tabular-nums text-blue-700 sm:border-0 sm:pt-0 sm:text-right">${{ number_format($fac->total_pagado, 2) }}</p>
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

    var narrowMq = window.matchMedia('(max-width: 639px)');
    function chartIsNarrow () {
        return narrowMq.matches;
    }

    var breakpointLayoutTimer;

    /** Repinta leyenda/ejes al cambiar el breakpoint sin forzar resize() manual (dispara mejor con ResizeObserver interno). */
    function applyBreakpointLayout (chart, debounceMs) {
        var narrow = chartIsNarrow();
        chart.options.plugins.legend.position = narrow ? 'bottom' : 'top';
        chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
        chart.options.plugins.legend.labels.padding = narrow ? 10 : 16;
        var xTicks = chart.options.scales.x.ticks;
        xTicks.maxRotation = narrow ? 45 : 0;
        xTicks.autoSkipPadding = narrow ? 4 : 8;
        chart.options.layout = chart.options.layout || {};
        chart.options.layout.padding = narrow
            ? { top: 0, right: 4, bottom: 8, left: 4 }
            : { top: 4, right: 8, bottom: 4, left: 4 };

        clearTimeout(breakpointLayoutTimer);
        var ms = typeof debounceMs === 'number' ? debounceMs : 140;
        breakpointLayoutTimer = setTimeout(function () {
            requestAnimationFrame(function () {
                chart.update();
            });
        }, ms);
    }

    var initialNarrow = chartIsNarrow();
    var chart = new Chart(canvas, {
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
            resizeDelay: 120,
            animation: false,
            layout: {
                padding: initialNarrow
                    ? { top: 0, right: 4, bottom: 8, left: 4 }
                    : { top: 4, right: 8, bottom: 4, left: 4 }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        autoSkip: true,
                        maxRotation: initialNarrow ? 45 : 0,
                        autoSkipPadding: initialNarrow ? 4 : 8,
                        font: { size: 10 }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        maxTicksLimit: 6,
                        font: { size: 10 },
                        callback: function (value) {
                            return '$' + Number(value).toLocaleString('es-SV', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: initialNarrow ? 'bottom' : 'top',
                    labels: {
                        boxWidth: 12,
                        padding: initialNarrow ? 10 : 16,
                        font: { size: 11 }
                    }
                },
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

    function onBreakpointChange () {
        applyBreakpointLayout(chart, 160);
    }
    if (narrowMq.addEventListener) {
        narrowMq.addEventListener('change', onBreakpointChange);
    } else if (narrowMq.addListener) {
        narrowMq.addListener(onBreakpointChange);
    }
});
</script>
@endpush
