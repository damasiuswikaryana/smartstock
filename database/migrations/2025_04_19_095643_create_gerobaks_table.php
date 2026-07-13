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
        Schema::create('gerobaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loc_id')->references('id')->on('outlets')->onDelete('cascade')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->string('kode', 100)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('lat', 25)->nullable();
            $table->string('long', 25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerobaks');
    }
};
