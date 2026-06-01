@extends('layouts.app')

@section('title', 'Administradores — Admin')

@section('content')
<div class="mb-8 flex flex-col gap-6 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Administradores</h1>
        <p class="mt-2 text-sm text-gray-500">Crea, edita o elimina cuentas con rol de administrador.</p>
    </div>

    <div class="flex w-full flex-wrap items-center gap-3 sm:w-auto">
        <form method="get" action="{{ route('admin.admins.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ $busqueda }}"
                   placeholder="Nombre o correo…"
                   class="min-w-[12rem] flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:flex-none sm:w-64">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">Buscar</button>
            @if($busqueda !== '')
                <a href="{{ route('admin.admins.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">Limpiar</a>
            @endif
        </form>
        <a href="{{ route('admin.admins.create') }}"
           class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
            + Nuevo admin
        </a>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-gray-900 lg:px-6">Nombre</th>
                    <th class="hidden px-4 py-4 text-left font-semibold text-gray-900 md:table-cell">Correo</th>
                    <th class="hidden px-4 py-4 text-left font-semibold text-gray-900 md:table-cell">Estado</th>
                    <th class="px-4 py-4 text-right font-semibold text-gray-900 lg:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($admins as $adm)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-normal px-4 py-4 lg:px-6">
                            <div class="font-medium text-gray-900">{{ $adm->name }}</div>
                            <div class="mt-0.5 text-xs text-gray-500 md:hidden">{{ $adm->email }}</div>
                        </td>
                        <td class="hidden px-4 py-4 text-gray-600 md:table-cell">{{ $adm->email }}</td>
                        <td class="hidden px-4 py-4 text-gray-600 md:table-cell">{{ $adm->estado }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right lg:px-6">
                            <a href="{{ route('admin.admins.edit', $adm->id) }}" class="font-semibold text-blue-600 hover:text-blue-800">Editar</a>
                            @if(auth()->id() === $adm->id)
                                <span class="ml-3 cursor-not-allowed text-xs text-gray-400" title="No puedes eliminar tu propia cuenta">Eliminar</span>
                            @else
                                <form method="post" action="{{ route('admin.admins.destroy', $adm->id) }}" class="ml-3 inline-block"
                                      onsubmit="return confirm('¿Eliminar este administrador?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-gray-500">No hay administradores que coincidan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($admins->hasPages())
    <div class="mt-6">{{ $admins->links() }}</div>
@endif
@endsection
