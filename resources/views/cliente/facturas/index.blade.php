@extends('layouts.app')

@section('title', 'Mis facturas — La Cuponera SV')

@section('content')
<div class="mx-auto max-w-5xl">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Mis facturas</h1>
    <p class="mt-2 text-sm text-gray-600">Compras registradas y comprobantes descargables.</p>

    @if($facturas->isEmpty())
        <div class="mt-10 rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-8 py-14 text-center">
            <p class="text-gray-700">Aún no tienes facturas.</p>
            <a href="{{ route('home') }}#ofertas" class="mt-4 inline-flex font-semibold text-blue-600 hover:text-blue-800">Explorar ofertas</a>
        </div>
    @else
        <div class="mt-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Factura</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Fecha</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Ítems</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Pago</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($facturas as $factura)
                            <tr class="hover:bg-gray-50/80">
                                <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-900">#{{ $factura->id_factura }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $factura->fecha_compra instanceof \Carbon\Carbon ? $factura->fecha_compra->timezone(config('app.timezone'))->format('d/m/Y H:i') : $factura->fecha_compra }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $factura->cupones_comprados_count }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">${{ number_format($factura->total_pagado, 2) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $factura->metodo_pago }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('cliente.facturas.show', $factura->id_factura) }}" class="font-semibold text-blue-600 hover:text-blue-800">Ver</a>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <a href="{{ route('cliente.facturas.pdf', $factura->id_factura) }}" class="font-semibold text-blue-600 hover:text-blue-800">PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($facturas->hasPages())
            <div class="mt-6">{{ $facturas->withQueryString()->links() }}</div>
        @endif
    @endif
</div>
@endsection
