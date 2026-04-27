@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Revisar Solicitud: {{ $empresa->nombre_empresa }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Datos de la Empresa</h5>
                    <p><strong>NIT:</strong> {{ $empresa->nit }}</p>
                    <p><strong>Teléfono:</strong> {{ $empresa->telefono }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->direccion }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-warning text-dark">Estado: {{ $empresa->estado_solicitud }}</span>
                </div>
            </div>

            <hr>

            <form action="{{ route('admin.aprobar', $empresa->id_empresa) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Asignar Porcentaje de Comisión (%)</label>
                    <input type="number" name="porcentaje_comision" id="porcentaje_comision" step="0.01" min="0" max="100" class="form-control" placeholder="Ejemplo: 10.50">
                    <div class="form-text">Este porcentaje se aplicará a cada cupón vendido por esta empresa.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="accion" value="aprobar" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Aprobar Empresa
                    </button>
                    <button type="submit" name="accion" value="rechazar" class="btn btn-danger px-4" onclick="return confirm('¿Estás seguro de rechazar esta solicitud?')">
                        <i class="bi bi-x-circle"></i> Rechazar
                    </button>
                    <a href="{{ route('admin.solicitudes') }}" class="btn btn-light border px-4">Cancelar</a>
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