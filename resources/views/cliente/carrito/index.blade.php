@extends('layouts.app')

@section('title', 'Carrito — La Cuponera SV')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Carrito</h1>
            <p class="mt-1 text-sm text-gray-600">Revisa las cantidades antes de pagar. Máximo 5 cupones por oferta.</p>
        </div>
        <a href="{{ route('home') }}#ofertas" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Seguir comprando</a>
    </div>

    @if($items === [])
        <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-8 py-16 text-center">
            <p class="text-gray-700">Tu carrito está vacío.</p>
            <a href="{{ route('home') }}#ofertas" class="mt-4 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Ver ofertas</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($items as $row)
                @php
                    $o = $row['oferta'];
                @endphp
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-blue-600">{{ $o->empresa->nombre_empresa ?? '—' }}</p>
                            <h2 class="mt-1 font-bold text-gray-900">
                                <a href="{{ route('ofertas.show', $o->id_oferta) }}" class="hover:text-blue-700">{{ $o->titulo }}</a>
                            </h2>
                            <p class="mt-2 text-sm text-gray-600">${{ number_format($o->precio_oferta, 2) }} c/u</p>
                            <p class="mt-1 text-xs text-gray-500">Máx. permitido ahora: {{ $row['max_permitido'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 sm:flex-col sm:items-end">
                            <form action="{{ route('cliente.carrito.update', $o->id_oferta) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="qty-{{ $o->id_oferta }}">Cantidad</label>
                                <select name="cantidad" id="qty-{{ $o->id_oferta }}" class="rounded-lg border border-gray-300 px-2 py-1.5 text-sm" onchange="this.form.requestSubmit()">
                                    @for($i = 0; $i <= $row['max_permitido']; $i++)
                                        <option value="{{ $i }}" @selected($i === (int) $row['cantidad'])>{{ $i === 0 ? 'Quitar' : $i }}</option>
                                    @endfor
                                </select>
                                <noscript>
                                    <button type="submit" class="rounded-lg bg-gray-100 px-2 py-1 text-xs font-medium">Actualizar</button>
                                </noscript>
                            </form>
                            <p class="text-sm font-semibold text-gray-900">Subtotal: ${{ number_format($row['subtotal'], 2) }}</p>
                            <form action="{{ route('cliente.carrito.destroy', $o->id_oferta) }}" method="POST" onsubmit="return confirm('¿Quitar esta oferta del carrito?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div class="text-left sm:text-right">
                    <p class="text-sm text-gray-600">Total a pagar</p>
                    <p class="text-2xl font-extrabold text-gray-900">${{ number_format($total, 2) }}</p>
                </div>
            </div>

            <form action="{{ route('cliente.carrito.checkout') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nombre_titular" class="mb-1 block text-sm font-medium text-gray-700">Nombre en la tarjeta</label>
                    <input type="text" name="nombre_titular" id="nombre_titular" value="{{ old('nombre_titular') }}" autocomplete="cc-name"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                           placeholder="Como aparece en la tarjeta" required>
                    @error('nombre_titular')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_tarjeta" class="mb-1 block text-sm font-medium text-gray-700">Número de tarjeta</label>
                    <input type="text" name="numero_tarjeta" id="numero_tarjeta" value="{{ old('numero_tarjeta') }}" inputmode="numeric" autocomplete="cc-number"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono tracking-wide"
                           placeholder="0000 0000 0000 0000" required maxlength="23">
                    @error('numero_tarjeta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="vencimiento" class="mb-1 block text-sm font-medium text-gray-700">Vencimiento</label>
                        <input type="text" name="vencimiento" id="vencimiento" value="{{ old('vencimiento') }}" autocomplete="cc-exp"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono"
                               placeholder="MM / AA" required maxlength="7">
                        @error('vencimiento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cvv" class="mb-1 block text-sm font-medium text-gray-700">CVC / CVV</label>
                        <input type="password" name="cvv" id="cvv" inputmode="numeric" autocomplete="cc-csc"
                               class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-mono"
                               placeholder="•••" required maxlength="4">
                        @error('cvv')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-8 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:w-auto">
                        Pagar ${{ number_format($total, 2) }} (simulado)
                    </button>
                </div>
            </form>
        </div>

        @error('carrito')
            <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const card = document.getElementById('numero_tarjeta');
    const exp = document.getElementById('vencimiento');
    if (card) {
        card.addEventListener('input', function () {
            const digits = this.value.replace(/\D/g, '').slice(0, 19);
            this.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
        });
    }
    if (exp) {
        exp.addEventListener('input', function () {
            const digits = this.value.replace(/\D/g, '').slice(0, 4);
            this.value = digits.length <= 2 ? digits : digits.slice(0, 2) + ' / ' + digits.slice(2);
        });
    }
})();
</script>
@endpush
