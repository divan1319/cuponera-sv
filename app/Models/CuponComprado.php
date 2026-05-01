<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuponComprado extends Model
{
    protected $table = 'cupones_comprados';

    protected $primaryKey = 'id_cupon';

    public $timestamps = false;

    protected $fillable = [
        'id_factura', 'id_oferta', 'codigo_unico',
        'precio_al_comprar', 'estado_canje', 'fecha_canje',
    ];

    protected function casts(): array
    {
        return [
            'fecha_canje' => 'datetime',
        ];
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'id_factura', 'id_factura');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta', 'id_oferta');
    }
}
