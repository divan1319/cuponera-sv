<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterClienteRequest;
use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Factura;
use App\Models\Rol;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function dashboard()
    {
        return view('cliente.dashboard');
    }

    public function cupones()
    {
        $cliente = auth()->user()->cliente;
        abort_unless($cliente, 403);

        $cupones = CuponComprado::query()
            ->whereHas('factura', fn ($q) => $q->where('id_cliente', $cliente->id_cliente))
            ->with(['oferta' => fn ($q) => $q->select('id_oferta', 'titulo')])
            ->orderByDesc('id_cupon')
            ->paginate(15);

        return view('cliente.cupones.index', compact('cupones'));
    }

    public function facturas()
    {
        $cliente = auth()->user()->cliente;
        abort_unless($cliente, 403);

        $facturas = Factura::query()
            ->where('id_cliente', $cliente->id_cliente)
            ->withCount('cuponesComprados')
            ->orderByDesc('fecha_compra')
            ->orderByDesc('id_factura')
            ->paginate(15);

        return view('cliente.facturas.index', compact('facturas'));
    }

    public function facturaShow(int $id_factura)
    {
        $factura = $this->resolveFacturaAutorizada($id_factura);

        return view('cliente.facturas.show', compact('factura'));
    }

    public function facturaPdf(int $id_factura)
    {
        $factura = $this->resolveFacturaAutorizada($id_factura);

        $pdf = Pdf::loadView('cliente.facturas.pdf', ['factura' => $factura]);

        return $pdf->download(sprintf('factura-%d.pdf', $factura->id_factura));
    }

    private function resolveFacturaAutorizada(int $id_factura): Factura
    {
        $cliente = auth()->user()->cliente;
        abort_unless($cliente, 403);

        return Factura::query()
            ->where('id_cliente', $cliente->id_cliente)
            ->where('id_factura', $id_factura)
            ->with([
                'cuponesComprados' => fn ($q) => $q->orderBy('id_cupon'),
                'cuponesComprados.oferta' => fn ($q) => $q->select('id_oferta', 'titulo'),
            ])
            ->firstOrFail();
    }

    public function showRegister()
    {
        return view('cliente.register');
    }

    public function register(RegisterClienteRequest $request)
    {
        DB::transaction(function () use ($request) {
            $rolCliente = Rol::where('nombre', 'Cliente')->firstOrFail();

            $nombresCompletos = trim($request->nombres.' '.$request->apellidos);

            $user = User::create([
                'name' => $nombresCompletos,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_rol' => $rolCliente->id_rol,
                'estado' => 'Activo',
            ]);

            Cliente::create([
                'user_id' => $user->id,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'dui' => $request->dui,
                'fecha_nacimiento' => $request->fecha_nacimiento,
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registro exitoso. Inicia sesión con tu correo y contraseña.');
    }
}
