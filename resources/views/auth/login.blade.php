@extends('layouts.app')
@section('title', 'Iniciar Sesión — La Cuponera SV')

@section('content')
<div class="mx-auto mt-12 max-w-md">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Bienvenido de nuevo</h1>
        <p class="mt-2 text-sm text-gray-500">Ingresa a tu cuenta para continuar</p>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-sm border border-gray-100">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="email" name="email"
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                       value="{{ old('email') }}" required autofocus>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>
            
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Recordarme
                </label>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="mt-2 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Ingresar
            </button>
        </form>

        <div class="mt-8 space-y-3">
            <p class="text-center text-sm text-gray-500">
                ¿Quieres comprar cupones?
                <a href="{{ route('cliente.register') }}" class="font-medium text-blue-600 hover:text-blue-500">Regístrate como cliente</a>
            </p>
            <p class="text-center text-sm text-gray-500">
                ¿Eres empresa?
                <a href="{{ route('empresa.register') }}" class="font-medium text-blue-600 hover:text-blue-500">Regístrate aquí</a>
            </p>
        </div>
    </div>
</div>
@endsection
