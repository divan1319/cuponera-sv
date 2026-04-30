@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Reporte General de Ganancias</h1>
        <p class="mt-2 text-sm text-gray-500">RF-04: Resumen de ingresos y comisiones por empresa.</p>
    </div>
    <div class="rounded-xl bg-green-50 px-4 py-3 border border-green-100">
        <p class="text-sm font-medium text-green-800">Total Plataforma</p>
        <p class="text-2xl font-bold text-green-700">${{ number_format($reporteData->sum('comision_ganada'), 2) }}</p>
    </div>
</div>

<div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Empresa</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Cupones Vendidos</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Total Ingresos</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Comisión (%)</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Ganancia Admin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach($reporteData as $data)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $data['nombre'] }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $data['cupones_vendidos'] }}</td>
                    <td class="px-6 py-4 text-gray-500">${{ number_format($data['total_ingresos'], 2) }}</td>
                    <td class="px-6 py-4 text-gray-500">{{-- Buscamos la empresa original para el % --}} %</td> 
                    <td class="px-6 py-4 font-bold text-green-600">${{ number_format($data['comision_ganada'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection