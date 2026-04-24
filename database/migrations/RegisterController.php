<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Validación: Debe ser una fecha válida y tener al menos 18 años de antigüedad
            'birth_date' => 'required|date|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
        ], [
            'birth_date.before' => 'Debes ser mayor de 18 años para registrarte.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
        ]);

        return response()->json(['message' => 'Registro exitoso'], 201);
    }
}