@extends('layouts.app')
@section('title', 'Iniciar Sesión — La Cuponera SV')

@section('content')
<div class="mx-auto mt-8 max-w-md">
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-2xl font-black text-blue-700">
            LC
        </div>
        <h1 class="mt-3 text-2xl font-bold text-slate-950">La Cuponera SV</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-semibold text-slate-950">Iniciar Sesión</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Correo electrónico</label>
                <input type="email" name="email"
                       class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('email') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                       value="{{ old('email') }}" required autofocus>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Contraseña</label>
                <input type="password" name="password" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" required>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                Recordarme
            </label>
            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                Ingresar
            </button>
        </form>

        <div class="my-5 border-t border-slate-200"></div>
        <p class="text-center text-sm text-slate-500">
            ¿Eres empresa y no tienes cuenta?
            <a href="{{ route('empresa.register') }}" class="font-medium text-blue-600 hover:text-blue-700">Regístrate aquí</a>
        </p>
        <!--Crear enlace para crear cuenta como cliente-->
        <p class="text-center text-sm text-slate-500">
            ¿Quieres comprar cupones y no tienes cuenta?
            <a href="{{ route('cliente.register') }}" class="font-medium text-blue-600 hover:text-blue-700">Regístrate aquí</a>
        </p>
    </div>
</div>
@endsection
