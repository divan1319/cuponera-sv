<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'ofertas';
    protected $primaryKey = 'id_oferta';
    public $timestamps = false;

    protected $fillable = [
        'id_empresa', 'titulo', 'precio_regular', 'precio_oferta',
        'fecha_inicio', 'fecha_fin', 'fecha_limite_canje',
        'cantidad_limite', 'descripcion', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'       => 'datetime',
            'fecha_fin'          => 'datetime',
            'fecha_limite_canje' => 'date',
            'fecha_creacion'     => 'datetime',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function cuponesComprados()
    {
        return $this->hasMany(CuponComprado::class, 'id_oferta', 'id_oferta');
    }
}
