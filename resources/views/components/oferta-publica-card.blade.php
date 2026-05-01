@props(['oferta'])

@php
    $vendidos = (int) $oferta->cupones_comprados_count;
    $limite = $oferta->cantidad_limite;
    $restantes = $limite !== null ? max(0, (int) $limite - $vendidos) : null;
    $agotado = $limite !== null && $restantes === 0;
    $hasta = $oferta->fecha_fin ?? $oferta->fecha_limite_canje;
    $descuento = $oferta->precio_regular > 0
        ? (int) round(100 - ($oferta->precio_oferta / $oferta->precio_regular) * 100)
        : null;
@endphp

<article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm ring-1 ring-black/5 transition duration-300 hover:-translate-y-1 hover:border-blue-200/80 hover:shadow-lg hover:shadow-blue-900/5 {{ $agotado ? 'opacity-[0.92]' : '' }}">
    @if($descuento !== null && $descuento > 0)
        <div class="flex justify-end bg-gradient-to-r from-slate-50 to-blue-50/80 px-4 py-2">
            <span class="rounded-lg bg-blue-600 px-2 py-0.5 text-xs font-bold text-white shadow-sm">-{{ $descuento }}%</span>
        </div>
    @endif
    <div class="flex flex-1 flex-col p-6">
        @if($oferta->empresa)
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ $oferta->empresa->nombre_empresa }}</p>
        @endif
        <h3 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-gray-900 group-hover:text-blue-900">{{ $oferta->titulo }}</h3>
        <div class="mt-4 flex flex-wrap items-baseline gap-2">
            <span class="text-sm text-gray-400 line-through">${{ number_format($oferta->precio_regular, 2) }}</span>
            <span class="text-2xl font-extrabold tracking-tight text-emerald-600">${{ number_format($oferta->precio_oferta, 2) }}</span>
        </div>
        <p class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
            <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
            Vigente hasta {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        </p>
        <div class="mt-5 border-t border-gray-100 pt-4">
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
                <span class="mt-0.5 block text-xs text-gray-500">Sin límite de unidades</span>
            @endif
        </div>
    </div>
</article>
