<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->increments('id_oferta');
            $table->unsignedInteger('id_empresa');
            $table->string('titulo');
            $table->decimal('precio_regular', 10, 2);
            $table->decimal('precio_oferta', 10, 2);
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin');
            $table->date('fecha_limite_canje');
            $table->integer('cantidad_limite')->nullable();
            $table->text('descripcion');
            $table->string('estado')->default('Disponible');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_empresa')->references('id_empresa')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
