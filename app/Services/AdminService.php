<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Rol;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

/**
 * Lógica de dominio del panel de administración: métricas, reportes, solicitudes,
 * ABM de empresas y clientes. Mantiene las consultas agrupadas y reutilizables fuera del controlador.
 */
class AdminService
{
    /**
     * Ajusta año y mes solicitados y devuelve el rango calendario completo de ese mes.
     *
     * @param  int  $year  Año solicitado (se limita a no ser menor que 1970).
     * @param  int  $month  Mes solicitado (se fuerza entre 1 y 12).
     * @return array{year: int, month: int, desde: Carbon, hasta: Carbon}
     */
    public function resolverPeriodoDashboard(int $year, int $month): array
    {
        $year = max(1970, $year);
        $month = min(12, max(1, $month));

        $desde = Carbon::createStrict($year, $month, 1)->startOfDay();
        $hasta = (clone $desde)->endOfMonth();

        return [
            'year' => $year,
            'month' => $month,
            'desde' => $desde,
            'hasta' => $hasta,
        ];
    }

    /**
     * Obtiene comisión de plataforma por día en un mes, usando agregación en base de datos,
     * y el total del mes.
     *
     * Inicializa cada día del mes en 0 y suma `precio_al_comprar * (porcentaje_comision/100)` por cupón.
     *
     * @return array{por_dia: array<string, float>, total_mes: float}
     *               `por_dia` usa claves `Y-m-d`.
     */
    public function comisionesPorDiaDelMes(Carbon $desde, Carbon $hasta): array
    {
        $porDia = $this->plantillaDiasDelMes($desde);

        [$sqlDiaSelect, $sqlDiaGroup] = $this->expresionSqlDiaCalendarioFactura('f.fecha_compra');

        $filas = DB::table('cupones_comprados as cc')
            ->join('facturas as f', 'cc.id_factura', '=', 'f.id_factura')
            ->join('ofertas as o', 'cc.id_oferta', '=', 'o.id_oferta')
            ->join('empresas as e', 'o.id_empresa', '=', 'e.id_empresa')
            ->whereBetween('f.fecha_compra', [$desde, $hasta])
            ->whereNotNull('e.porcentaje_comision')
            ->selectRaw("{$sqlDiaSelect} as dia")
            ->selectRaw('SUM(cc.precio_al_comprar * (e.porcentaje_comision / 100)) as ganancia')
            ->groupByRaw($sqlDiaGroup)
            ->get();

        foreach ($filas as $fila) {
            $clave = (string) $fila->dia;
            if (! array_key_exists($clave, $porDia)) {
                continue;
            }
            $porDia[$clave] = round((float) $fila->ganancia, 2);
        }

        $totalMes = round(array_sum($porDia), 2);

        return [
            'por_dia' => $porDia,
            'total_mes' => $totalMes,
        ];
    }

    /**
     * Fragmentos SQL para agrupar facturas por día calendario según el driver activo
     * (MySQL/MariaDB, SQLite o PostgreSQL).
     *
     * @param  string  $columnaFactura  Referencia calificada, p. ej. `f.fecha_compra`.
     * @return array{0: string, 1: string} [expresión para SELECT, misma expresión para GROUP BY]
     */
    protected function expresionSqlDiaCalendarioFactura(string $columnaFactura): array
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => ["date({$columnaFactura})", "date({$columnaFactura})"],
            'pgsql' => ["({$columnaFactura})::date", "({$columnaFactura})::date"],
            default => ["DATE({$columnaFactura})", "DATE({$columnaFactura})"],
        };
    }

    /**
     * Construye un arreglo con cada día del mes en `Y-m-d` inicializado en 0.0.
     *
     * @return array<string, float>
     */
    protected function plantillaDiasDelMes(Carbon $inicioMes): array
    {
        $porDia = [];
        $fin = $inicioMes->copy()->endOfMonth();

        foreach (CarbonPeriod::create($inicioMes->copy()->startOfDay(), $fin->copy()->endOfDay()) as $fecha) {
            $porDia[$fecha->toDateString()] = 0.0;
        }

        return $porDia;
    }

    /**
     * Últimas facturas con cliente, cupones y oferta/empresa, para el panel de administración.
     *
     * @return Collection<int, Factura>
     */
    public function ultimasFacturasConDetalle(int $limite = 10): Collection
    {
        return Factura::query()
            ->with([
                'cliente',
                'cuponesComprados.oferta' => fn ($q) => $q->with('empresa:id_empresa,nombre_empresa'),
            ])
            ->orderByDesc('fecha_compra')
            ->limit($limite)
            ->get();
    }

    /**
     * Años consecutivos desde el año actual hasta el de la primera factura (como máximo 20 años atrás),
     * para poblar el selector del dashboard.
     *
     * @return list<int>
     */
    public function añosDisponiblesParaDashboard(): array
    {
        $fcMin = Factura::query()->min('fecha_compra');
        $floorYear = $fcMin ? min(Carbon::parse($fcMin)->year, now()->year) : now()->year;
        $floorYear = max($floorYear, now()->year - 20);

        return range(now()->year, $floorYear);
    }

    /**
     * Empresas con solicitud de registro en estado pendiente (RF-01).
     *
     * @return Collection<int, Empresa>
     */
    public function listarSolicitudesPendientes(): Collection
    {
        return Empresa::query()->where('estado_solicitud', 'Pendiente')->get();
    }

    /**
     * Aprueba una solicitud de empresa y persiste el porcentaje de comisión.
     */
    public function aprobarSolicitudEmpresa(Empresa $empresa, mixed $porcentajeComision): void
    {
        $empresa->update([
            'estado_solicitud' => 'Aprobada',
            'porcentaje_comision' => $porcentajeComision,
        ]);
    }

    /**
     * Rechaza una solicitud de empresa.
     */
    public function rechazarSolicitudEmpresa(Empresa $empresa): void
    {
        $empresa->update(['estado_solicitud' => 'Rechazada']);
    }

    /**
     * Arma filas de reporte por empresa aprobada: cupones vendidos, ingresos brutos y comisión.
     *
     * Usa `withCount` sobre ofertas para no cargar todos los cupones en memoria.
     *
     * @return SupportCollection<int, array{
     *     nombre: string,
     *     cupones_vendidos: int,
     *     total_ingresos: float|int,
     *     comision_ganada: float|int,
     *     porcentaje_comision: float|int|string|null
     * }>
     */
    public function datosReportePorEmpresaAprobada(): SupportCollection
    {
        $empresas = Empresa::query()
            ->where('estado_solicitud', 'Aprobada')
            ->with(['ofertas' => fn ($q) => $q->withCount('cuponesComprados')])
            ->get();

        return $empresas->map(function (Empresa $empresa) {
            $totalVendido = 0;

            foreach ($empresa->ofertas as $oferta) {
                $totalVendido += (int) $oferta->cupones_comprados_count * (float) $oferta->precio_oferta;
            }

            $porcentaje = (float) $empresa->porcentaje_comision;
            $gananciaPlataforma = $totalVendido * ($porcentaje / 100);

            return [
                'nombre' => $empresa->nombre_empresa,
                'cupones_vendidos' => (int) $empresa->ofertas->sum('cupones_comprados_count'),
                'total_ingresos' => $totalVendido,
                'comision_ganada' => $gananciaPlataforma,
                'porcentaje_comision' => $empresa->porcentaje_comision,
            ];
        });
    }

    /**
     * Listado paginado de empresas con búsqueda opcional por nombre o NIT.
     *
     * @param  string  $busqueda  Término sin espacios extremos; vacío omite el filtro.
     */
    public function paginarEmpresasAdministracion(string $busqueda, int $porPagina = 12): LengthAwarePaginator
    {
        $query = Empresa::query()->with('user:id,name,email');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre_empresa', 'like', '%'.$busqueda.'%')
                    ->orWhere('nit', 'like', '%'.$busqueda.'%');
            });
        }

        return $query
            ->withCount([
                'cuponesComprados as ventas_total',
            ])
            ->orderBy('nombre_empresa')
            ->paginate($porPagina)
            ->withQueryString();
    }

    /**
     * Cantidad de cupones vendidos asociados a la empresa (vía ofertas).
     */
    public function contarVentasEmpresa(Empresa $empresa): int
    {
        return (int) $empresa->cuponesComprados()->count();
    }

    /**
     * Persiste los datos validados de una empresa; incluye comisión solo si la solicitud está aprobada.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizarEmpresaDesdeAdministracion(Empresa $empresa, array $datos): void
    {
        if ($empresa->estado_solicitud === 'Aprobada') {
            $empresa->update([
                'nombre_empresa' => $datos['nombre_empresa'],
                'nit' => $datos['nit'],
                'direccion' => $datos['direccion'],
                'telefono' => $datos['telefono'],
                'porcentaje_comision' => $datos['porcentaje_comision'],
            ]);

            return;
        }

        $empresa->update([
            'nombre_empresa' => $datos['nombre_empresa'],
            'nit' => $datos['nit'],
            'direccion' => $datos['direccion'],
            'telefono' => $datos['telefono'],
        ]);
    }

    /**
     * Indica si la empresa tiene al menos un cupón vendido (no se debe eliminar en ese caso).
     */
    public function empresaTieneVentas(Empresa $empresa): bool
    {
        return $empresa->cuponesComprados()->exists();
    }

    /**
     * Elimina ofertas de la empresa, la empresa y su usuario en una transacción.
     *
     * Debe llamarse solo si `empresaTieneVentas` es false.
     */
    public function eliminarEmpresaUsuarioYOfertas(Empresa $empresa): void
    {
        DB::transaction(function () use ($empresa) {
            $userId = $empresa->user_id;
            $empresa->ofertas()->delete();
            $empresa->delete();
            User::query()->where('id', $userId)->delete();
        });
    }

    /**
     * Listado paginado de clientes con búsqueda en datos personales o usuario.
     *
     * @param  string  $busqueda  Término recortado; vacío omite filtros.
     */
    public function paginarClientesAdministracion(string $busqueda, int $porPagina = 15): LengthAwarePaginator
    {
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

        return $query
            ->withCount('cuponesComprados')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate($porPagina)
            ->withQueryString();
    }

    /**
     * Métricas agregadas para la ficha de cliente en administración.
     *
     * Reduce lecturas a una consulta de conteo de facturas y otra agregada sobre cupones.
     *
     * @return array{
     *     tiene_cupones_comprados: bool,
     *     compras_count: int,
     *     canjeados: int,
     *     no_canjeados: int
     * }
     */
    public function metricasClienteAdministracion(Cliente $cliente): array
    {
        $comprasCount = (int) Factura::query()
            ->where('id_cliente', $cliente->id_cliente)
            ->count();

        $agg = CuponComprado::query()
            ->whereHas('factura', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
            ->selectRaw(
                'COUNT(*) as total, SUM(CASE WHEN estado_canje = ? THEN 1 ELSE 0 END) as canjeados, SUM(CASE WHEN estado_canje = ? THEN 1 ELSE 0 END) as no_canjeados',
                ['Canjeado', 'No Canjeado']
            )
            ->first();

        $total = (int) ($agg?->total ?? 0);

        return [
            'tiene_cupones_comprados' => $total > 0,
            'compras_count' => $comprasCount,
            'canjeados' => (int) ($agg?->canjeados ?? 0),
            'no_canjeados' => (int) ($agg?->no_canjeados ?? 0),
        ];
    }

    /**
     * Indica si el cliente tiene cupones comprados (vía facturas).
     */
    public function clienteTieneCuponesComprados(Cliente $cliente): bool
    {
        return CuponComprado::query()
            ->whereHas('factura', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
            ->exists();
    }

    /**
     * Borra facturas del cliente (y cupones de cada factura) y el usuario asociado.
     *
     * Solo debe usarse cuando `clienteTieneCuponesComprados` es false (no hay cupones);
     * en ese caso normalmente no hay facturas, pero el método limpia por consistencia.
     */
    public function eliminarClienteFacturasYUsuario(Cliente $cliente): void
    {
        $cliente->loadMissing('facturas');

        DB::transaction(function () use ($cliente) {
            foreach ($cliente->facturas as $factura) {
                $factura->cuponesComprados()->delete();
                $factura->delete();
            }
            User::query()->where('id', $cliente->user_id)->delete();
        });
    }

    /**
     * Listado paginado de administradores con búsqueda opcional por nombre o correo.
     *
     * @param  string  $busqueda  Término recortado; vacío omite filtros.
     */
    public function paginarAdminsAdministracion(string $busqueda, int $porPagina = 15): LengthAwarePaginator
    {
        $query = User::query()
            ->whereHas('rol', fn ($q) => $q->where('nombre', 'Admin'));

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('name', 'like', '%'.$busqueda.'%')
                    ->orWhere('email', 'like', '%'.$busqueda.'%');
            });
        }

        return $query
            ->orderBy('name')
            ->paginate($porPagina)
            ->withQueryString();
    }

    /**
     * Cantidad total de administradores existentes (sin filtro de estado).
     */
    public function contarAdmins(): int
    {
        return (int) User::query()
            ->whereHas('rol', fn ($q) => $q->where('nombre', 'Admin'))
            ->count();
    }

    /**
     * Crea un nuevo usuario con rol Admin.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crearAdmin(array $datos): User
    {
        $idRolAdmin = (int) Rol::query()->where('nombre', 'Admin')->value('id_rol');

        return User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'],
            'id_rol' => $idRolAdmin,
            'estado' => 'Activo',
        ]);
    }

    /**
     * Actualiza nombre/correo de un administrador. La contraseña se actualiza solo si viene informada.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizarAdmin(User $admin, array $datos): void
    {
        $payload = [
            'name' => $datos['name'],
            'email' => $datos['email'],
        ];

        if (! empty($datos['password'])) {
            $payload['password'] = $datos['password'];
        }

        $admin->update($payload);
    }

    /**
     * Elimina un administrador.
     */
    public function eliminarAdmin(User $admin): void
    {
        $admin->delete();
    }
}
