@extends('layouts.app')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="bg-blue-700 px-6 py-5 text-white">
        <h1 class="text-xl font-semibold">Gestión de Solicitudes de empresas</h1>
    </div>
    <div class="p-6">
        <p class="mb-5 text-sm text-slate-500">A continuación se muestran las empresas que han solicitado registrarse en la plataforma y esperan aprobación.</p>
        
        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Nombre de la Empresa</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">NIT</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Teléfono</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Estado Actual</th>
                        <th class="px-4 py-3 text-center font-semibold text-slate-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($solicitudes as $empresa)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-950">{{ $empresa->nombre_empresa }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $empresa->nit }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $empresa->telefono }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                    {{ $empresa->estado_solicitud }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.revisar', $empresa->id_empresa) }}" class="inline-flex rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">
                                    Revisar y Aprobar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                No hay solicitudes pendientes en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection