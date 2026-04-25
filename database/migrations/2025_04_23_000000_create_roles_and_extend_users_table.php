<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id_rol');
            $table->string('nombre', 50);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->nullable()->after('id');
            $table->string('estado')->default('Activo')->after('remember_token');
            $table->foreign('id_rol')->references('id_rol')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
            $table->dropColumn(['id_rol', 'estado']);
        });

        Schema::dropIfExists('roles');
    }
};
