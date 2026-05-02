<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación de la decisión del administrador sobre una solicitud de empresa (aprobar o rechazar).
 */
class AdminProcesarSolicitudEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string|\Illuminate\Contracts\Validation\ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'accion' => ['required', 'string', Rule::in(['aprobar', 'rechazar'])],
            'porcentaje_comision' => [
                Rule::requiredIf(fn () => $this->input('accion') === 'aprobar'),
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'accion.required' => 'Debe elegir aprobar o rechazar.',
            'accion.in' => 'La acción indicada no es válida.',
            'porcentaje_comision.required' => 'Debe asignar una comisión para aprobar.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'accion' => 'acción',
            'porcentaje_comision' => 'porcentaje de comisión',
        ];
    }
}
