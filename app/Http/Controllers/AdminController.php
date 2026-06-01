<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminActualizarEmpresaRequest;
use App\Http\Requests\AdminGuardarAdminRequest;
use App\Http\Requests\AdminProcesarSolicitudEmpresaRequest;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $admin
    ) {}

    public function dashboard(Request $request)
    {
        $periodo = $this->admin->resolverPeriodoDashboard(
            (int) $request->integer('year', now()->year),
            (int) $request->integer('month', now()->month),
        );

        $comisiones = $this->admin->comisionesPorDiaDelMes($periodo['desde'], $periodo['hasta']);

        $porDia = $comisiones['por_dia'];
        $totalMes = $comisiones['total_mes'];
        $year = $periodo['year'];
        $month = $periodo['month'];

        $ultimasCompras = $this->admin->ultimasFacturasConDetalle(10);
        $yearChoices = $this->admin->añosDisponiblesParaDashboard();

        return view('admin.dashboard', compact(
            'porDia',
            'totalMes',
            'year',
            'month',
            'ultimasCompras',
            'yearChoices'
        ));
    }

    /**
     * Muestra el listado de empresas con solicitud pendiente (RF-01)
     */
    public function listarSolicitudes()
    {
        $solicitudes = $this->admin->listarSolicitudesPendientes();

        return view('admin.solicitudes', compact('solicitudes'));
    }

    public function revisar($id)
    {
        $empresa = Empresa::findOrFail($id);

        return view('admin.revisar_solicitud', compact('empresa'));
    }

    public function procesar(AdminProcesarSolicitudEmpresaRequest $request)
    {
        $empresa = Empresa::findOrFail((int) $request->route('id'));
        $data = $request->validated();

        if ($data['accion'] === 'aprobar') {
            $this->admin->aprobarSolicitudEmpresa($empresa, $data['porcentaje_comision']);

            return redirect()->route('admin.solicitudes')->with('success', 'Empresa aprobada');
        }

        $this->admin->rechazarSolicitudEmpresa($empresa);

        return redirect()->route('admin.solicitudes')->with('error', 'Solicitud rechazada');
    }

    public function verReportes()
    {
        $reporteData = $this->admin->datosReportePorEmpresaAprobada();

        return view('admin.reportes', compact('reporteData'));
    }

    public function empresasIndex(Request $request)
    {
        $busqueda = $request->string('q')->trim()->toString();
        $empresas = $this->admin->paginarEmpresasAdministracion($busqueda, 12);

        return view('admin.empresas.index', compact('empresas', 'busqueda'));
    }

    public function empresasEdit(int $id)
    {
        $empresa = Empresa::findOrFail($id);
        $ventasCount = $this->admin->contarVentasEmpresa($empresa);

        return view('admin.empresas.edit', compact('empresa', 'ventasCount'));
    }

    public function empresasUpdate(AdminActualizarEmpresaRequest $request)
    {
        $empresa = $request->empresa();

        $this->admin->actualizarEmpresaDesdeAdministracion($empresa, $request->validated());

        return redirect()->route('admin.empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function empresasDestroy(int $id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($this->admin->empresaTieneVentas($empresa)) {
            return redirect()->back()->with('error', 'No se puede eliminar esta empresa porque ya tiene ventas registradas.');
        }

        $this->admin->eliminarEmpresaUsuarioYOfertas($empresa);

        return redirect()->route('admin.empresas.index')->with('success', 'Empresa eliminada.');
    }

    public function clientesIndex(Request $request)
    {
        $busqueda = $request->string('q')->trim()->toString();
        $clientes = $this->admin->paginarClientesAdministracion($busqueda, 15);

        return view('admin.clientes.index', compact('clientes', 'busqueda'));
    }

    public function clientesShow(int $id)
    {
        $cliente = Cliente::with('user')->findOrFail($id);

        $metricas = $this->admin->metricasClienteAdministracion($cliente);

        $tieneCuponesComprados = $metricas['tiene_cupones_comprados'];
        $comprasCount = $metricas['compras_count'];
        $canjeados = $metricas['canjeados'];
        $noCanjeados = $metricas['no_canjeados'];

        return view('admin.clientes.show', compact(
            'cliente',
            'tieneCuponesComprados',
            'comprasCount',
            'canjeados',
            'noCanjeados'
        ));
    }

    public function clientesDestroy(int $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($this->admin->clienteTieneCuponesComprados($cliente)) {
            return redirect()->back()->with(
                'error',
                'No se puede eliminar este cliente porque tiene cupones comprados.'
            );
        }

        $this->admin->eliminarClienteFacturasYUsuario($cliente);

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente eliminado.');
    }

    public function adminsIndex(Request $request)
    {
        $busqueda = $request->string('q')->trim()->toString();
        $admins = $this->admin->paginarAdminsAdministracion($busqueda, 15);

        return view('admin.admins.index', compact('admins', 'busqueda'));
    }

    public function adminsCreate()
    {
        return view('admin.admins.create');
    }

    public function adminsStore(AdminGuardarAdminRequest $request)
    {
        $this->admin->crearAdmin($request->validated());

        return redirect()->route('admin.admins.index')->with('success', 'Administrador creado correctamente.');
    }

    public function adminsEdit(int $id)
    {
        $admin = User::query()
            ->whereHas('rol', fn ($q) => $q->where('nombre', 'Admin'))
            ->findOrFail($id);

        return view('admin.admins.edit', compact('admin'));
    }

    public function adminsUpdate(AdminGuardarAdminRequest $request)
    {
        $admin = $request->admin();

        $this->admin->actualizarAdmin($admin, $request->validated());

        return redirect()->route('admin.admins.index')->with('success', 'Administrador actualizado correctamente.');
    }

    public function adminsDestroy(int $id)
    {
        $admin = User::query()
            ->whereHas('rol', fn ($q) => $q->where('nombre', 'Admin'))
            ->findOrFail($id);

        if (Auth::id() === $admin->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        if ($this->admin->contarAdmins() <= 1) {
            return redirect()->back()->with('error', 'No se puede eliminar el último administrador del sistema.');
        }

        $this->admin->eliminarAdmin($admin);

        return redirect()->route('admin.admins.index')->with('success', 'Administrador eliminado.');
    }
}
