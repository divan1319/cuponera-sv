@extends('layouts.app')

@section('title', 'Factura #'.$factura->id_factura.' — La Cuponera SV')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Factura #{{ $factura->id_factura }}</h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ $factura->fecha_compra instanceof \Carbon\Carbon ? $factura->fecha_compra->timezone(config('app.timezone'))->format('d/m/Y \a \l\a\s H:i') : $factura->fecha_compra }}
            </p>
        </div>
        <a href="{{ route('cliente.facturas.pdf', $factura->id_factura) }}"
           class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            Descargar PDF
        </a>
    </div>

    <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="font-medium text-gray-500">Cliente</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->cliente->nombres }} {{ Auth::user()->cliente->apellidos }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Método de pago</dt>
                <dd class="mt-1 text-gray-900">{{ $factura->metodo_pago }}</dd>
            </div>
        </dl>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 bg-gray-50 px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-800">Detalle</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Oferta</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Código</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Precio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($factura->cuponesComprados as $cupon)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">{{ $cupon->oferta->titulo ?? '—' }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-900">{{ $cupon->codigo_unico }}</td>
                            <td class="px-4 py-3 text-right text-gray-900">${{ number_format($cupon->precio_al_comprar, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                        <td class="px-4 py-3 text-right text-base font-bold text-gray-900">${{ number_format($factura->total_pagado, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <p class="mt-8 text-center">
        <a href="{{ route('cliente.facturas.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">&larr; Volver al listado</a>
    </p>
</div>
@endsection
