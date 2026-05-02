<?php

namespace App\Http\Controllers;

use App\Events\EmpresaSolicitudRegistrada;
use App\Models\CuponComprado;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpresaController extends Controller
{
    public function showRegister()
    {
        return view('empresa.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nombre_empresa' => 'required|string|max:150',
            'nit' => 'required|string|max:20|unique:empresas,nit',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'nit.unique' => 'Este NIT ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        [$representante, $empresa] = DB::transaction(function () use ($request) {
            $rolEmpresa = Rol::where('nombre', 'Empresa')->firstOrFail();

            $representante = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_rol' => $rolEmpresa->id_rol,
                'estado' => 'Activo',
            ]);

            $empresa = Empresa::create([
                'user_id' => $representante->id,
                'nombre_empresa' => $request->nombre_empresa,
                'nit' => $request->nit,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'estado_solicitud' => 'Pendiente',
            ]);

            return [$representante, $empresa];
        });

        event(new EmpresaSolicitudRegistrada($representante, $empresa));

        return redirect()->route('login')
            ->with('success', 'Solicitud enviada. Un administrador revisará tu registro pronto.');
    }

    public function dashboard()
    {
        $empresa = Auth::user()->empresa;

        $totalOfertas = $empresa->ofertas()->count();
        $ofertasDisponibles = $empresa->ofertas()->where('estado', 'Disponible')->count();

        $ofertaIds = $empresa->ofertas()->pluck('id_oferta');

        $cuponesVendidos = CuponComprado::whereIn('id_oferta', $ofertaIds)->count();
        $cuponesPendientes = CuponComprado::whereIn('id_oferta', $ofertaIds)
            ->where('estado_canje', 'No Canjeado')
            ->count();

        return view('empresa.dashboard', compact(
            'empresa',
            'totalOfertas',
            'ofertasDisponibles',
            'cuponesVendidos',
            'cuponesPendientes'
        ));
    }
}
