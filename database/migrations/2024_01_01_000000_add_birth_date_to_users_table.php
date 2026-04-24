<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $columnas) {
            $columnas->date('birth_date')->after('email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function ($columnas) { $columnas->dropColumn('birth_date'); });
    }
};