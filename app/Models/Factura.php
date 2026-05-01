<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_factura', 'id_cliente', 'fecha_compra', 'total_pagado', 'metodo_pago'])]
#[Table('facturas',key: 'id_factura')]
class Factura extends Model
{
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function cuponesComprados()
    {
        return $this->hasMany(CuponComprado::class, 'id_factura', 'id_factura');
    }
}
