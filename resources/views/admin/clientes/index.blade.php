@extends('layouts.app')

@section('title', 'Clientes — Admin')

@section('content')
<div class="mb-8 flex flex-col gap-6 sm:flex-row sm:flex-wrap sm:items-end sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Clientes</h1>
        <p class="mt-2 text-sm text-gray-500">Consulta detalle o elimina clientes sin historial de cupones comprados.</p>
    </div>

    <form method="get" action="{{ route('admin.clientes.index') }}" class="flex w-full flex-wrap gap-2 sm:w-auto">
        <input type="text" name="q" value="{{ $busqueda }}"
               placeholder="Nombre, DUI, correo…"
               class="min-w-[12rem] flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:flex-none sm:w-64">
        <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200">Buscar</button>
        @if($busqueda !== '')
            <a href="{{ route('admin.clientes.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">Limpiar</a>
        @endif
    </form>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-gray-900 lg:px-6">Cliente</th>
                    <th class="hidden px-4 py-4 text-left font-semibold text-gray-900 md:table-cell">DUI</th>
                    <th class="px-4 py-4 text-right font-semibold text-gray-900 lg:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($clientes as $cli)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-normal px-4 py-4 lg:px-6">
                            <div class="font-medium text-gray-900">{{ trim($cli->nombres.' '.$cli->apellidos) }}</div>
                            @if($cli->user)
                                <div class="mt-0.5 text-xs text-gray-500">{{ $cli->user->email }}</div>
                            @endif
                        </td>
                        <td class="hidden px-4 py-4 text-gray-600 md:table-cell">{{ $cli->dui }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right lg:px-6">
                            <a href="{{ route('admin.clientes.show', $cli->id_cliente) }}" class="font-semibold text-blue-600 hover:text-blue-800">Ver detalle</a>
                            @if($cli->cupones_comprados_count > 0)
                                <span class="ml-3 cursor-not-allowed text-xs text-gray-400" title="No se puede eliminar: tiene cupones comprados">Eliminar</span>
                            @else
                                <form method="post" action="{{ route('admin.clientes.destroy', $cli->id_cliente) }}" class="ml-3 inline-block"
                                      onsubmit="return confirm('¿Eliminar este cliente y su cuenta de usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:text-red-800">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center text-gray-500">No hay clientes que coincidan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($clientes->hasPages())
    <div class="mt-6">{{ $clientes->links() }}</div>
@endif
@endsection
