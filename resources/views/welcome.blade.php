@extends('layouts.app')
@section('title', 'La Cuponera SV')

@section('content')
<div class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-2xl font-black text-blue-700">
        LC
    </div>
    <h1 class="mt-5 text-3xl font-bold tracking-tight text-slate-950">La Cuponera SV</h1>
    <p class="mt-3 text-slate-500">
        Plataforma para registrar empresas, publicar ofertas y administrar cupones.
    </p>
    <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="{{ route('login') }}" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            Iniciar sesión
        </a>
        <a href="{{ route('empresa.register') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Registrar empresa
        </a>
    </div>
</div>
@endsection
