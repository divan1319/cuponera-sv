<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación del formulario de edición de empresa en el panel de administración.
 */
class AdminActualizarEmpresaRequest extends FormRequest
{
    private ?Empresa $empresaRuta = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Empresa identificada por el parámetro de ruta `{id}` (clave primaria `id_empresa`).
     */
    public function empresa(): Empresa
    {
        return $this->empresaRuta ??= Empresa::findOrFail((int) $this->route('id'));
    }

    /**
     * @return array<string, list<string|\Illuminate\Contracts\Validation\ValidationRule>|string>
     */
    public function rules(): array
    {
        $empresa = $this->empresa();

        $rules = [
            'nombre_empresa' => ['required', 'string', 'max:150'],
            'nit' => ['required', 'string', 'max:20', 'unique:empresas,nit,'.$empresa->id_empresa.',id_empresa'],
            'direccion' => ['required', 'string'],
            'telefono' => ['required', 'string', 'max:20'],
        ];

        if ($empresa->estado_solicitud === 'Aprobada') {
            $rules['porcentaje_comision'] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre_empresa' => 'nombre de la empresa',
            'nit' => 'NIT',
            'direccion' => 'dirección',
            'telefono' => 'teléfono',
            'porcentaje_comision' => 'porcentaje de comisión',
        ];
    }
}
