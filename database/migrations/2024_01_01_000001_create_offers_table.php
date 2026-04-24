<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->string('title');
            $tabla->text('description');
            $tabla->decimal('price', 8, 2);
            $tabla->integer('stock');
            $tabla->date('expires_at');
            $tabla->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('offers'); }
};