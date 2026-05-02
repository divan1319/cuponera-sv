@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-8">
        <a href="{{ route('admin.solicitudes') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-4">
            <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver a solicitudes
        </a>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Revisar Solicitud</h1>
        <p class="mt-2 text-sm text-gray-500">Revisa los datos de la empresa y asigna una comisión para aprobarla.</p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-8">
        <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $empresa->nombre_empresa }}</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-600">
                    <p class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <strong>NIT:</strong> {{ $empresa->nit }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.48-4.18-7.074-7.074l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        <strong>Teléfono:</strong> {{ $empresa->telefono }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <strong>Dirección:</strong> {{ $empresa->direccion }}
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-sm font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                Estado: {{ $empresa->estado_solicitud }}
            </span>
        </div>

        <div class="my-8 border-t border-gray-100"></div>

        <form action="{{ route('admin.aprobar', $empresa->id_empresa) }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-8">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Asignar Porcentaje de Comisión (%)</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="number" name="porcentaje_comision" id="porcentaje_comision" value="{{ old('porcentaje_comision') }}" step="0.01" min="0" max="100" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('porcentaje_comision') border-red-500 ring-1 ring-red-500 @enderror" placeholder="Ejemplo: 10.50">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <span class="text-gray-500 sm:text-sm">%</span>
                    </div>
                </div>
                @error('porcentaje_comision')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Este porcentaje se aplicará a cada cupón vendido por esta empresa.</p>
            </div>

            <div class="flex flex-wrap gap-4">
                <button type="submit" name="accion" value="aprobar" class="rounded-xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Aprobar Empresa
                </button>
                <button type="submit" name="accion" value="rechazar" class="rounded-xl bg-white border border-red-200 px-6 py-3 text-sm font-semibold text-red-600 shadow-sm transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="return confirm('¿Estás seguro de rechazar esta solicitud?')">
                    Rechazar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection