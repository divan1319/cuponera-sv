@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-blue-700 px-6 py-5 text-white">
            <h1 class="text-xl font-semibold">Revisar Solicitud: {{ $empresa->nombre_empresa }}</h1>
        </div>
        <div class="p-6">
            <div class="mb-6 flex flex-wrap justify-between gap-4">
                <div>
                    <h2 class="mb-3 font-semibold text-slate-950">Datos de la Empresa</h2>
                    <div class="space-y-2 text-sm text-slate-700">
                        <p><strong>NIT:</strong> {{ $empresa->nit }}</p>
                        <p><strong>Teléfono:</strong> {{ $empresa->telefono }}</p>
                        <p><strong>Dirección:</strong> {{ $empresa->direccion }}</p>
                    </div>
                </div>
                <div>
                    <span class="rounded-full bg-amber-100 px-3 py-1.5 text-sm font-semibold text-amber-800">Estado: {{ $empresa->estado_solicitud }}</span>
                </div>
            </div>

            <div class="my-6 border-t border-slate-200"></div>

            <form action="{{ route('admin.aprobar', $empresa->id_empresa) }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Asignar Porcentaje de Comisión (%)</label>
                    <input type="number" name="porcentaje_comision" id="porcentaje_comision" step="0.01" min="0" max="100" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="Ejemplo: 10.50">
                    <p class="mt-1 text-sm text-slate-500">Este porcentaje se aplicará a cada cupón vendido por esta empresa.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" name="accion" value="aprobar" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Aprobar Empresa
                    </button>
                    <button type="submit" name="accion" value="rechazar" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700" onclick="return confirm('¿Estás seguro de rechazar esta solicitud?')">
                        Rechazar
                    </button>
                    <a href="{{ route('admin.solicitudes') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Validar que la comisión solo sea obligatoria al presionar "Aprobar"
    document.querySelector('button[value="aprobar"]').addEventListener('click', function(e) {
        const comision = document.getElementById('porcentaje_comision').value;
        if (!comision) {
            e.preventDefault();
            alert('Por favor, asigna un porcentaje de comisión para aprobar la empresa.');
        }
    });
</script>
@endsection