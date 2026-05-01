@extends('layouts.app')

@section('title', 'Mis cupones — La Cuponera SV')

@section('content')
<div class="mx-auto max-w-5xl">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Mis cupones</h1>
    <p class="mt-2 text-sm text-gray-600">Códigos generados al completar una compra.</p>

    @if($cupones->isEmpty())
        <div class="mt-10 rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-8 py-14 text-center">
            <p class="text-gray-700">Aún no tienes cupones.</p>
            <a href="{{ route('home') }}#ofertas" class="mt-4 inline-flex font-semibold text-blue-600 hover:text-blue-800">Explorar ofertas</a>
        </div>
    @else
        <div class="mt-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Código</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Oferta</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Precio</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cupones as $cupon)
                            <tr class="hover:bg-gray-50/80">
                                <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-900">{{ $cupon->codigo_unico }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $cupon->oferta->titulo ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-700">${{ number_format($cupon->precio_al_comprar, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if($cupon->estado_canje === 'Canjeado')
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">Canjeado</span>
                                    @else
                                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-800">Válido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($cupones->hasPages())
            <div class="mt-6">{{ $cupones->withQueryString()->links() }}</div>
        @endif
    @endif
</div>
@endsection
