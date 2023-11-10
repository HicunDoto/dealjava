<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('varian_products', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->bigInteger('price')->nullable();
            $table->integer('status')->nullable(); // 0 = tidak aktif, 1 = aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varian_products');
    }
};
