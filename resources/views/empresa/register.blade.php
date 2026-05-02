@extends('layouts.app')
@section('title', 'Registro de Empresa — La Cuponera SV')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500';
    $errorInputClass = 'border-red-400 focus:border-red-500 focus:ring-red-500';
@endphp

<div class="mx-auto mt-8 max-w-3xl">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Registro de Empresa</h1>
        <p class="mt-2 text-sm text-gray-500">Tu solicitud será revisada y aprobada por un administrador</p>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-100">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.register.store') }}">
            @csrf

            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Datos del representante</h2>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nombre completo</label>
                        <input type="text" name="name" class="{{ $inputClass }} @error('name') {{ $errorInputClass }} @enderror" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="email" class="{{ $inputClass }} @error('email') {{ $errorInputClass }} @enderror" value="{{ old('email') }}" required>
                        @error('email')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password" class="{{ $inputClass }}" required minlength="8">
                        <p class="mt-1.5 text-xs text-gray-500">Mínimo 8 caracteres.</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="{{ $inputClass }}" required>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Datos de la empresa</h2>
                <div class="grid gap-5 md:grid-cols-12">
                    <div class="md:col-span-8">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nombre de la empresa</label>
                        <input type="text" name="nombre_empresa" class="{{ $inputClass }} @error('nombre_empresa') {{ $errorInputClass }} @enderror" value="{{ old('nombre_empresa') }}" required>
                        @error('nombre_empresa')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">NIT</label>
                        <input type="text" name="nit" id="registro-nit" inputmode="numeric" autocomplete="off" maxlength="17" class="{{ $inputClass }} @error('nit') {{ $errorInputClass }} @enderror" value="{{ old('nit') }}" placeholder="0000-000000-000-0" required>
                        @error('nit')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-8">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" class="{{ $inputClass }} @error('direccion') {{ $errorInputClass }} @enderror" value="{{ old('direccion') }}" required>
                        @error('direccion')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="registro-telefono" inputmode="numeric" autocomplete="tel-national" maxlength="9" class="{{ $inputClass }} @error('telefono') {{ $errorInputClass }} @enderror" value="{{ old('telefono') }}" placeholder="2222-3333" required>
                        @error('telefono')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Enviar Solicitud de Registro
            </button>
        </form>

        <div class="mt-8 border-t border-gray-100 pt-6">
            <p class="text-center text-sm text-gray-500">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    function setupDigitMask(el, maxDigits, formatDigits) {
        if (!el) return;

        function applyMask(raw) {
            const digits = String(raw).replace(/\D/g, '').slice(0, maxDigits);
            return formatDigits(digits);
        }

        el.addEventListener('input', function () {
            const start = el.selectionStart;
            const before = el.value;
            const digitsLeftOfCaret = before.slice(0, start).replace(/\D/g, '').length;
            el.value = applyMask(el.value);
            if (digitsLeftOfCaret === 0) {
                el.setSelectionRange(0, 0);
                return;
            }
            let pos = el.value.length;
            let seen = 0;
            for (let i = 0; i < el.value.length; i++) {
                if (/\d/.test(el.value[i])) seen++;
                if (seen >= digitsLeftOfCaret) {
                    pos = i + 1;
                    break;
                }
            }
            el.setSelectionRange(pos, pos);
        });

        el.value = applyMask(el.value);
    }

    function formatNitDigits(d) {
        if (d.length <= 4) return d;
        let out = d.slice(0, 4) + '-' + d.slice(4, 10);
        if (d.length <= 10) return out;
        out = d.slice(0, 4) + '-' + d.slice(4, 10) + '-' + d.slice(10, 13);
        if (d.length <= 13) return out;
        return d.slice(0, 4) + '-' + d.slice(4, 10) + '-' + d.slice(10, 13) + '-' + d.slice(13, 14);
    }

    function formatTelefonoDigits(d) {
        if (d.length <= 4) return d;
        return d.slice(0, 4) + '-' + d.slice(4, 8);
    }

    setupDigitMask(document.getElementById('registro-nit'), 14, formatNitDigits);
    setupDigitMask(document.getElementById('registro-telefono'), 8, formatTelefonoDigits);
})();
</script>
@endpush
