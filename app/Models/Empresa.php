<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'nombre_empresa', 'nit', 'direccion',
        'telefono', 'estado_solicitud', 'porcentaje_comision',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_empresa', 'id_empresa');
    }

    public function cuponesComprados()
    {
        return $this->hasManyThrough(CuponComprado::class, Oferta::class, 'id_empresa', 'id_oferta', 'id_empresa', 'id_oferta')
        ->with('oferta')
        ->with('ventas');
    }
}
