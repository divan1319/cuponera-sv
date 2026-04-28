@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4>RF-04: Reporte General de Ganancias</h4>
            <span class="badge bg-success">Total Plataforma: ${{ number_format($reporteData->sum('comision_ganada'), 2) }}</span>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Cupones Vendidos</th>
                        <th>Total Ingresos</th>
                        <th>Comisión (%)</th>
                        <th>Ganancia Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporteData as $data)
                    <tr>
                        <td>{{ $data['nombre'] }}</td>
                        <td>{{ $data['cupones_vendidos'] }}</td>
                        <td>${{ number_format($data['total_ingresos'], 2) }}</td>
                        <td>{{-- Buscamos la empresa original para el % --}} %</td> 
                        <td class="fw-bold text-success">${{ number_format($data['comision_ganada'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection