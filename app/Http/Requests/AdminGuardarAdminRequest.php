<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación del formulario para crear o actualizar un usuario administrador.
 */
class AdminGuardarAdminRequest extends FormRequest
{
    private ?User $adminRuta = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Administrador identificado por el parámetro de ruta `{id}` (clave primaria `id`).
     * Devuelve null en creación.
     */
    public function admin(): ?User
    {
        if ($this->adminRuta !== null) {
            return $this->adminRuta;
        }

        $id = $this->route('id');
        if ($id === null) {
            return null;
        }

        return $this->adminRuta = User::query()->findOrFail((int) $id);
    }

    /**
     * @return array<string, list<string|\Illuminate\Contracts\Validation\ValidationRule>|string>
     */
    public function rules(): array
    {
        $admin = $this->admin();
        $esCreacion = $admin === null;

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required', 'email', 'max:191',
                Rule::unique('users', 'email')->ignore($admin?->id, 'id'),
            ],
            'password' => $esCreacion
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }
}
