<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('original_price', 8, 2);
            $table->decimal('discount_price', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('stock');
            $table->enum('status', ['active', 'expired', 'sold_out'])->default('active');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
