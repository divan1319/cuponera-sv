@extends('layouts.app')

@section('title', 'Empresas — Admin')

@section('content')
<div class="mb-8 flex flex-col gap-6 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Empresas</h1>
        <p class="mt-2 text-sm text-gray-500">Gestiona datos de empresa. Solo se permite eliminar si no tiene ventas.</p>
    </div>

    <form method="get" action="{{ route('admin.empresas.index') }}" class="flex w-full flex-wrap gap-2 sm:w-auto">
        <input type="text" name="q" value="{{ $busqueda }}"
               placeholder="Buscar nombre o NIT…"
               class="min-w-[12rem] flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:flex-none sm:w-64">
        <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">Buscar</button>
        @if($busqueda !== '')
            <a href="{{ route('admin.empresas.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">Limpiar</a>
        @endif
    </form>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-gray-900 lg:px-6">Empresa</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-900">NIT</th>
                    <th class="hidden px-4 py-4 text-left font-semibold text-gray-900 md:table-cell">Teléfono</th>
                    <th class="px-4 py-4 text-left font-semibold text-gray-900">Estado</th>
                    <th class="px-4 py-4 text-center font-semibold text-gray-900">Ventas</th>
                    <th class="px-4 py-4 text-right font-semibold text-gray-900 lg:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($empresas as $em)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-normal px-4 py-4 font-medium text-gray-900 lg:px-6">
                            <div>{{ $em->nombre_empresa }}</div>
                            @if($em->user)
                                <div class="mt-0.5 text-xs font-normal text-gray-500">{{ $em->user->email }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-600">{{ $em->nit }}</td>
                        <td class="hidden px-4 py-4 text-gray-600 md:table-cell">{{ $em->telefono }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                @class([
                                    'bg-amber-100 text-amber-800' => $em->estado_solicitud === 'Pendiente',
                                    'bg-emerald-100 text-emerald-800' => $em->estado_solicitud === 'Aprobada',
                                    'bg-red-100 text-red-800' => $em->estado_solicitud === 'Rechazada',
                                    'bg-gray-100 text-gray-700' => !in_array($em->estado_solicitud, ['Pendiente','Aprobada','Rechazada'], true),
                                ])">
                                {{ $em->estado_solicitud }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-gray-700">{{ $em->ventas_total }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right lg:px-6">
                            <a href="{{ route('admin.empresas.edit', $em->id_empresa) }}" class="font-semibold text-blue-600 hover:text-blue-800">Editar</a>
                            @if($em->ventas_total > 0)
                                <span class="ml-3 cursor-not-allowed text-xs text-gray-400" title="No se puede eliminar: tiene ventas">Eliminar</span>
                            @else
                                <form method="post" action="{{ route('admin.empresas.destroy', $em->id_empresa) }}" class="ml-3 inline-block"
                                      onsubmit="return confirm('¿Eliminar empresa y cuenta de usuario? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">No hay empresas que coincidan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($empresas->hasPages())
    <div class="mt-6">{{ $empresas->links() }}</div>
@endif
@endsection
