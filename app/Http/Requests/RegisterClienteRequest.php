<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fechaLimiteMayorEdad = now()->subYears(18)->format('Y-m-d');

        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nombres' => 'required|string|min:4|max:100',
            'apellidos' => 'required|string|min:4|max:100',
            'dui' => 'required|string|max:10|unique:clientes,dui',
            'fecha_nacimiento' => [
                'required',
                'date',
                'before:today',
                'before_or_equal:' . $fechaLimiteMayorEdad,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado.',
            'dui.unique' => 'Este DUI ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de edad (18 años o más).',
            'nombres.min' => 'El nombre debe tener al menos 4 caracteres.',
            'apellidos.min' => 'El apellido debe tener al menos 4 caracteres.',
        ];
    }
}
