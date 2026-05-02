<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'precio_regular' => 'required|numeric|min:0.01',
            'precio_oferta' => 'required|numeric|min:0.01|lt:precio_regular',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_canje' => 'required|date|after_or_equal:fecha_fin',
            'cantidad_limite' => 'nullable|integer|min:1',
            'descripcion' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'precio_oferta.lt' => 'El precio de oferta debe ser menor al precio regular.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'fecha_limite_canje.after_or_equal' => 'La fecha límite de canje debe ser igual o posterior a la fecha de fin.',
        ];
    }
}
