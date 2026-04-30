@extends('layouts.app')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-900 px-6 py-5 text-white">
        <h1 class="text-xl font-semibold">RF-04: Reporte General de Ganancias</h1>
        <span class="rounded-full bg-emerald-500/20 px-3 py-1.5 text-sm font-semibold text-emerald-100">Total Plataforma: ${{ number_format($reporteData->sum('comision_ganada'), 2) }}</span>
    </div>
    <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Empresa</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Cupones Vendidos</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Total Ingresos</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Comisión (%)</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Ganancia Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reporteData as $data)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-slate-950">{{ $data['nombre'] }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $data['cupones_vendidos'] }}</td>
                        <td class="px-4 py-3 text-slate-700">${{ number_format($data['total_ingresos'], 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{-- Buscamos la empresa original para el % --}} %</td> 
                        <td class="px-4 py-3 font-bold text-emerald-700">${{ number_format($data['comision_ganada'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>
@endsection