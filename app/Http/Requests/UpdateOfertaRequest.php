<?php

namespace App\Http\Requests;

class UpdateOfertaRequest extends StoreOfertaRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'estado' => 'required|in:Disponible,No Disponible',
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'estado.required' => 'El estado es requerido.',
            'estado.in' => 'El estado debe ser Disponible o No Disponible.',
        ]);
    }
}
