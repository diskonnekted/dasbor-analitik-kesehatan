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
        Schema::create('sarana_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->cascadeOnDelete();
            $table->integer('tahun')->default(2024);
            $table->integer('puskesmas_pembantu')->default(0);
            $table->integer('puskesmas_keliling')->default(0);
            $table->integer('toko_obat')->default(0);
            $table->integer('laborat')->default(0);
            $table->integer('apotek')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana_kesehatans');
    }
};
