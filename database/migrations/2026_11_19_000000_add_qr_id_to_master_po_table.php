<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah relasi PO ke QR (Quotation Request). Nullable karena PO lama belum punya QR.
     */
    public function up(): void
    {
        Schema::table('master_po', function (Blueprint $table) {
            $table->unsignedBigInteger('qr_id')->nullable()->after('prf_number');
            $table->foreign('qr_id')->references('id')->on('master_qr')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('master_po', function (Blueprint $table) {
            $table->dropForeign(['qr_id']);
            $table->dropColumn('qr_id');
        });
    }
};
