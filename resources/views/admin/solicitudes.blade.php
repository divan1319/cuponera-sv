@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Gestión de Solicitudes de Empresas</h1>
    <p class="mt-2 text-sm text-gray-500">A continuación se muestran las empresas que han solicitado registrarse en la plataforma y esperan aprobación.</p>
</div>

<div class="rounded-2xl bg-white shadow-sm border border-gray-100">
    @if(session('success'))
        <div class="m-6 mb-0 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="m-6 mb-0 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Nombre de la Empresa</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">NIT</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Teléfono</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Estado Actual</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($solicitudes as $empresa)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $empresa->nombre_empresa }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $empresa->nit }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $empresa->telefono }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                {{ $empresa->estado_solicitud }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.revisar', $empresa->id_empresa) }}" class="inline-flex items-center justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-blue-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                                Revisar y Aprobar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Sin solicitudes</h3>
                            <p class="mt-1 text-sm text-gray-500">No hay solicitudes pendientes en este momento.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection