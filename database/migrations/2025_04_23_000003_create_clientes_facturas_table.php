<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id_cliente');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('dui', 10)->unique();
            $table->date('fecha_nacimiento');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id_factura');
            $table->unsignedInteger('id_cliente');
            $table->timestamp('fecha_compra')->useCurrent();
            $table->decimal('total_pagado', 10, 2);
            $table->string('metodo_pago')->default('Tarjeta (Simulada)');
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('clientes');
    }
};
