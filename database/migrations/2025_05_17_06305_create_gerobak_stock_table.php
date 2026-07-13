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
        Schema::create('gerobak_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gerobak_id')->references('id')->on('gerobaks')->onDelete('cascade')->nullable();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade')->nullable();
            $table->integer('stock')->nullable();
            $table->date('stock_date')->nullable();
            $table->foreignId('admin_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerobak_stock');
    }
};
