<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa; // <--- IMPORTANTE: Importar el modelo

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Muestra el listado de empresas con solicitud pendiente (RF-01)
     */
    public function listarSolicitudes()
    {
        // Traemos solo las empresas que están esperando aprobación
        // Nota: Asegúrate de que tu tabla 'empresas' tenga una columna 'estado'
        $solicitudes = Empresa::where('estado_solicitud', 'Pendiente')->get();

        return view('admin.solicitudes', compact('solicitudes'));
    }

   // Muestra el formulario detallado
public function revisar($id) {
    $empresa = Empresa::findOrFail($id);
    return view('admin.revisar_solicitud', compact('empresa'));
}

// Procesa la aprobación o rechazo
public function procesar(Request $request, $id) 
{
    $empresa = Empresa::findOrFail($id);
    
    if ($request->accion == 'aprobar') {
        // Validación extra en el servidor solo para aprobación
        if (!$request->porcentaje_comision) {
            return redirect()->back()->with('error', 'Debe asignar una comisión para aprobar.');
        }

        $empresa->update([
            'estado_solicitud' => 'Aprobada',
            'porcentaje_comision' => $request->porcentaje_comision
        ]);
        return redirect()->route('admin.solicitudes')->with('success', 'Empresa aprobada');
    }
    
    // El rechazo ahora pasará directamente sin pedir comisión
    $empresa->update(['estado_solicitud' => 'Rechazada']);
    return redirect()->route('admin.solicitudes')->with('error', 'Solicitud rechazada');
}

public function verReportes()
{
    // Obtenemos las empresas aprobadas con sus cupones y ventas
    $empresas = Empresa::where('estado_solicitud', 'Aprobada')
        ->with(['ofertas.cuponesComprados']) 
        ->get();

    $reporteData = $empresas->map(function($empresa) {
        $totalVendido = 0;
        
        // Por cada oferta: unidades vendidas × precio (cupones están en oferta.cuponesComprados)
        foreach ($empresa->ofertas as $oferta) {
            $totalVendido += $oferta->cuponesComprados->count() * $oferta->precio_oferta;
        }

        $gananciaPlataforma = $totalVendido * ($empresa->porcentaje_comision / 100);

        return [
            'nombre' => $empresa->nombre_empresa,
            'cupones_vendidos' => $empresa->ofertas->sum(fn ($o) => $o->cuponesComprados->count()),
            'total_ingresos' => $totalVendido,
            'comision_ganada' => $gananciaPlataforma,
            'porcentaje_comision' => $empresa->porcentaje_comision
        ];
    });

    return view('admin.reportes', compact('reporteData'));
}

}
