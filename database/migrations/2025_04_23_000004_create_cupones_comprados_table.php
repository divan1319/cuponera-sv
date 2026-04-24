<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones_comprados', function (Blueprint $table) {
            $table->increments('id_cupon');
            $table->unsignedInteger('id_factura');
            $table->unsignedInteger('id_oferta');
            $table->string('codigo_unico', 50)->unique();
            $table->decimal('precio_al_comprar', 10, 2);
            $table->string('estado_canje')->default('No Canjeado');
            $table->timestamp('fecha_canje')->nullable();
            $table->foreign('id_factura')->references('id_factura')->on('facturas');
            $table->foreign('id_oferta')->references('id_oferta')->on('ofertas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones_comprados');
    }
};
