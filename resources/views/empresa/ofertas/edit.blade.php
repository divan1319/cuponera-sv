@extends('layouts.app')
@section('title', 'Editar Oferta — La Cuponera SV')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $errorInputClass = 'border-red-400 focus:border-red-500 focus:ring-red-100';
@endphp

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('empresa.ofertas.index') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
        Volver
    </a>
    <h1 class="text-2xl font-bold text-slate-950">Editar Oferta</h1>
</div>

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.ofertas.update', $oferta->id_oferta) }}">
            @csrf
            @method('PUT')
            <div class="grid gap-4 md:grid-cols-12">

                <div class="md:col-span-12">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Título de la oferta <span class="text-red-600">*</span></label>
                    <input type="text" name="titulo"
                           class="{{ $inputClass }} @error('titulo') {{ $errorInputClass }} @enderror"
                           value="{{ old('titulo', $oferta->titulo) }}" required>
                    @error('titulo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Precio Regular ($) <span class="text-red-600">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500">$</span>
                        <input type="number" name="precio_regular" step="0.01" min="0.01"
                               class="w-full rounded-r-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('precio_regular') {{ $errorInputClass }} @enderror"
                               value="{{ old('precio_regular', $oferta->precio_regular) }}" required>
                    </div>
                    @error('precio_regular')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-6">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Precio de Oferta ($) <span class="text-red-600">*</span></label>
                    <div class="flex">
                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500">$</span>
                        <input type="number" name="precio_oferta" step="0.01" min="0.01"
                               class="w-full rounded-r-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('precio_oferta') {{ $errorInputClass }} @enderror"
                               value="{{ old('precio_oferta', $oferta->precio_oferta) }}" required>
                    </div>
                    @error('precio_oferta')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Fecha y Hora de Inicio <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="fecha_inicio"
                           class="{{ $inputClass }} @error('fecha_inicio') {{ $errorInputClass }} @enderror"
                           value="{{ old('fecha_inicio', \Carbon\Carbon::parse($oferta->fecha_inicio)->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_inicio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Fecha y Hora de Fin <span class="text-red-600">*</span></label>
                    <input type="datetime-local" name="fecha_fin"
                           class="{{ $inputClass }} @error('fecha_fin') {{ $errorInputClass }} @enderror"
                           value="{{ old('fecha_fin', \Carbon\Carbon::parse($oferta->fecha_fin)->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_fin')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Fecha Límite de Canje <span class="text-red-600">*</span></label>
                    <input type="date" name="fecha_limite_canje"
                           class="{{ $inputClass }} @error('fecha_limite_canje') {{ $errorInputClass }} @enderror"
                           value="{{ old('fecha_limite_canje', \Carbon\Carbon::parse($oferta->fecha_limite_canje)->format('Y-m-d')) }}" required>
                    @error('fecha_limite_canje')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Cantidad Límite</label>
                    <input type="number" name="cantidad_limite" min="1"
                           class="{{ $inputClass }} @error('cantidad_limite') {{ $errorInputClass }} @enderror"
                           value="{{ old('cantidad_limite', $oferta->cantidad_limite) }}" placeholder="Sin límite">
                    @error('cantidad_limite')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-4">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Estado <span class="text-red-600">*</span></label>
                    <select name="estado" class="{{ $inputClass }} @error('estado') {{ $errorInputClass }} @enderror" required>
                        <option value="Disponible"
                            {{ old('estado', $oferta->estado) === 'Disponible' ? 'selected' : '' }}>
                            Disponible
                        </option>
                        <option value="No Disponible"
                            {{ old('estado', $oferta->estado) === 'No Disponible' ? 'selected' : '' }}>
                            No Disponible
                        </option>
                    </select>
                    @error('estado')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-12">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Descripción <span class="text-red-600">*</span></label>
                    <textarea name="descripcion" rows="4"
                              class="{{ $inputClass }} @error('descripcion') {{ $errorInputClass }} @enderror"
                              required>{{ old('descripcion', $oferta->descripcion) }}</textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Actualizar Oferta
                </button>
                <a href="{{ route('empresa.ofertas.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancelar</a>
            </div>
        </form>

</div>
@endsection
