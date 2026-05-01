<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterClienteRequest;
use App\Models\Cliente;
use App\Models\CuponComprado;
use App\Models\Rol;
use App\Models\User;
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
