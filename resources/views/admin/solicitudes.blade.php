@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Gestión de Solicitudes de empresas</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">A continuación se muestran las empresas que han solicitado registrarse en la plataforma y esperan aprobación.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre de la Empresa</th>
                                    <th>NIT</th>
                                    <th>Teléfono</th>
                                    <th>Estado Actual</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes as $empresa)
                                    <tr>
                                        <td class="fw-bold">{{ $empresa->nombre_empresa }}</td>
                                        <td>{{ $empresa->nit }}</td>
                                        <td>{{ $empresa->telefono }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-warning text-dark">
                                                {{ $empresa->estado_solicitud }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Este botón llevará al formulario de aprobación (RF-02) --}}
                                            <a href="{{ route('admin.revisar', $empresa->id_empresa) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> Revisar y Aprobar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No hay solicitudes pendientes en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection