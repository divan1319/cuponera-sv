<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id_empresa');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nombre_empresa', 150);
            $table->string('nit', 20)->unique();
            $table->text('direccion');
            $table->string('telefono', 20);
            $table->string('estado_solicitud')->default('Pendiente');
            $table->decimal('porcentaje_comision', 5, 2)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
