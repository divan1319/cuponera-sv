@extends('layouts.app')
@section('title', 'La Cuponera SV')

@section('content')
<div class="mx-auto mt-12 max-w-4xl text-center">
    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
        Descubre las mejores <span class="text-blue-600">ofertas</span>
    </h1>
    <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-500">
        La Cuponera SV es la plataforma ideal para encontrar descuentos increíbles y para que las empresas publiquen sus mejores promociones.
    </p>
    
    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
        <a href="{{ route('login') }}" class="w-full sm:w-auto rounded-full bg-blue-600 px-8 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow-md">
            Iniciar sesión
        </a>
        <a href="{{ route('cliente.register') }}" class="w-full sm:w-auto rounded-full bg-white border border-gray-300 px-8 py-3.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-gray-900">
            Regístrate como cliente
        </a>
        <a href="{{ route('empresa.register') }}" class="w-full sm:w-auto rounded-full bg-white border border-gray-300 px-8 py-3.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-gray-900">
            Registrar empresa
        </a>
    </div>
</div>
@endsection
