<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $year = max(1970, (int) $request->integer('year', now()->year));
        $month = min(12, max(1, (int) $request->integer('month', now()->month)));

        $desde = Carbon::createStrict($year, $month, 1)->startOfDay();
        $hasta = (clone $desde)->copy()->endOfMonth();

        $porDia = [];
        foreach (CarbonPeriod::create($desde->copy()->startOfDay(), $desde->copy()->endOfMonth()) as $fecha) {
            $porDia[$fecha->toDateString()] = 0.0;
        }

        $cuponesMes = CuponComprado::query()
            ->whereHas('factura', fn ($q) => $q->whereBetween('fecha_compra', [$desde, $hasta]))
            ->with([
                'factura:id_factura,fecha_compra',
                'oferta.empresa:id_empresa,porcentaje_comision',
            ])
            ->get();

        foreach ($cuponesMes as $cupon) {
            $factura = $cupon->factura;
            if (! $factura) {
                continue;
            }

            $comisionPct = $cupon->oferta?->empresa?->porcentaje_comision;
            if ($comisionPct === null) {
                continue;
            }

            $diaKey = Carbon::parse($factura->fecha_compra)->toDateString();

            $ganancia = (float) $cupon->precio_al_comprar * ((float) $comisionPct / 100);

            if (! array_key_exists($diaKey, $porDia)) {
                continue;
            }

            $porDia[$diaKey] = round($porDia[$diaKey] + $ganancia, 2);
        }

        $totalMes = round(array_sum($porDia), 2);

        $ultimasCompras = Factura::query()
            ->with([
                'cliente',
                'cuponesComprados.oferta' => fn ($q) => $q->with('empresa:id_empresa,nombre_empresa'),
            ])
            ->orderByDesc('fecha_compra')
            ->limit(10)
            ->get();

        $fcMin = Factura::query()->min('fecha_compra');
        $floorYear = $fcMin ? min(Carbon::parse($fcMin)->year, now()->year) : now()->year;
        $floorYear = max($floorYear, now()->year - 20);
        $yearChoices = range(now()->year, $floorYear);

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
        $solicitudes = Empresa::where('estado_solicitud', 'Pendiente')->get();

        return view('admin.solicitudes', compact('solicitudes'));
    }

    public function revisar($id)
    {
        $empresa = Empresa::findOrFail($id);

        return view('admin.revisar_solicitud', compact('empresa'));
    }

    public function procesar(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($request->accion === 'aprobar') {
            if (! $request->porcentaje_comision) {
                return redirect()->back()->with('error', 'Debe asignar una comisión para aprobar.');
            }

            $empresa->update([
                'estado_solicitud' => 'Aprobada',
                'porcentaje_comision' => $request->porcentaje_comision,
            ]);

            return redirect()->route('admin.solicitudes')->with('success', 'Empresa aprobada');
        }

        $empresa->update(['estado_solicitud' => 'Rechazada']);

        return redirect()->route('admin.solicitudes')->with('error', 'Solicitud rechazada');
    }

    public function verReportes()
    {
        $empresas = Empresa::where('estado_solicitud', 'Aprobada')
            ->with(['ofertas.cuponesComprados'])
            ->get();

        $reporteData = $empresas->map(function ($empresa) {
            $totalVendido = 0;

            foreach ($empresa->ofertas as $oferta) {
                $totalVendido += $oferta->cuponesComprados->count() * $oferta->precio_oferta;
            }

            $gananciaPlataforma = $totalVendido * ($empresa->porcentaje_comision / 100);

            return [
                'nombre' => $empresa->nombre_empresa,
                'cupones_vendidos' => $empresa->ofertas->sum(fn ($o) => $o->cuponesComprados->count()),
                'total_ingresos' => $totalVendido,
                'comision_ganada' => $gananciaPlataforma,
                'porcentaje_comision' => $empresa->porcentaje_comision,
            ];
        });

        return view('admin.reportes', compact('reporteData'));
    }

    public function empresasIndex(Request $request)
    {
        $busqueda = $request->string('q')->trim()->toString();

        $query = Empresa::query()->with('user:id,name,email');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre_empresa', 'like', '%'.$busqueda.'%')
                    ->orWhere('nit', 'like', '%'.$busqueda.'%');
            });
        }

        $empresas = $query
            ->withCount([
                'cuponesComprados as ventas_total',
            ])
            ->orderBy('nombre_empresa')
            ->paginate(12)
            ->withQueryString();

        return view('admin.empresas.index', compact('empresas', 'busqueda'));
    }

    public function empresasEdit(int $id)
    {
        $empresa = Empresa::findOrFail($id);
        $ventasCount = $empresa->cuponesComprados()->count();

        return view('admin.empresas.edit', compact('empresa', 'ventasCount'));
    }

    public function empresasUpdate(Request $request, int $id)
    {
        $empresa = Empresa::findOrFail($id);

        $rules = [
            'nombre_empresa' => ['required', 'string', 'max:150'],
            'nit' => ['required', 'string', 'max:20', 'unique:empresas,nit,'.$empresa->id_empresa.',id_empresa'],
            'direccion' => ['required', 'string'],
            'telefono' => ['required', 'string', 'max:20'],
        ];

        if ($empresa->estado_solicitud === 'Aprobada') {
            $rules['porcentaje_comision'] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        $data = $request->validate($rules);

        if ($empresa->estado_solicitud === 'Aprobada') {
            $empresa->update([
                'nombre_empresa' => $data['nombre_empresa'],
                'nit' => $data['nit'],
                'direccion' => $data['direccion'],
                'telefono' => $data['telefono'],
                'porcentaje_comision' => $data['porcentaje_comision'],
            ]);
        } else {
            $empresa->update([
                'nombre_empresa' => $data['nombre_empresa'],
                'nit' => $data['nit'],
                'direccion' => $data['direccion'],
                'telefono' => $data['telefono'],
            ]);
        }

        return redirect()->route('admin.empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function empresasDestroy(int $id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->cuponesComprados()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar esta empresa porque ya tiene ventas registradas.');
        }

        DB::transaction(function () use ($empresa) {
            $userId = $empresa->user_id;
            $empresa->ofertas()->delete();
            $empresa->delete();
            User::where('id', $userId)->delete();
        });

        return redirect()->route('admin.empresas.index')->with('success', 'Empresa eliminada.');
    }

    public function clientesIndex(Request $request)
    {
        $busqueda = $request->string('q')->trim()->toString();

        $query = Cliente::query()->with('user:id,name,email');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', '%'.$busqueda.'%')
                    ->orWhere('apellidos', 'like', '%'.$busqueda.'%')
                    ->orWhere('dui', 'like', '%'.$busqueda.'%')
                    ->orWhereHas('user', function ($uq) use ($busqueda) {
                        $uq->where('name', 'like', '%'.$busqueda.'%')
                            ->orWhere('email', 'like', '%'.$busqueda.'%');
                    });
            });
        }

        $clientes = $query
            ->withCount('cuponesComprados')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes', 'busqueda'));
    }

    public function clientesShow(int $id)
    {
        $cliente = Cliente::with('user')->findOrFail($id);

        $tieneCuponesComprados = CuponComprado::whereHas(
            'factura',
            fn ($q) => $q->where('id_cliente', $cliente->id_cliente)
        )->exists();

        $comprasCount = Factura::where('id_cliente', $cliente->id_cliente)->count();

        $canjeados = CuponComprado::whereHas(
            'factura',
            fn ($q) => $q->where('id_cliente', $cliente->id_cliente)
        )->where('estado_canje', 'Canjeado')->count();

        $noCanjeados = CuponComprado::whereHas(
            'factura',
            fn ($q) => $q->where('id_cliente', $cliente->id_cliente)
        )->where('estado_canje', 'No Canjeado')->count();

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

        $tieneCuponesComprados = CuponComprado::whereHas(
            'factura',
            fn ($q) => $q->where('id_cliente', $cliente->id_cliente)
        )->exists();

        if ($tieneCuponesComprados) {
            return redirect()->back()->with(
                'error',
                'No se puede eliminar este cliente porque tiene cupones comprados.'
            );
        }

        $cliente->loadMissing('facturas');

        DB::transaction(function () use ($cliente) {
            foreach ($cliente->facturas as $factura) {
                $factura->cuponesComprados()->delete();
                $factura->delete();
            }
            User::where('id', $cliente->user_id)->delete();
        });

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente eliminado.');
    }
}
