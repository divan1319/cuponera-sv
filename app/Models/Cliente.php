<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_cliente', 'user_id', 'nombres', 'apellidos', 'dui', 'fecha_nacimiento'])]
#[Table('clientes', key: 'id_cliente', timestamps: false)]
class Cliente extends Model{
  
    public function facturas()
    {
        return $this->hasMany(Factura::class, 'id_cliente', 'id_cliente');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}