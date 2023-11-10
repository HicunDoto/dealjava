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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('grup_product_id')->unsigned();
            $table->foreign('grup_product_id')->nullable()->references('id')->on('group_products')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->nullable()->references('id')->on('customers')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
