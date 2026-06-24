<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenaga_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faskes_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nama')->nullable(); // Menjadi nullable karena data open data berbentuk agregat, namun jika individual bisa diisi
            $table->integer('dokter_umum')->default(0);
            $table->integer('dokter_gigi')->default(0);
            $table->integer('perawat')->default(0);
            $table->integer('bidan')->default(0);
            $table->integer('farmasi')->default(0);
            $table->integer('kesmas')->default(0);
            $table->integer('kesling')->default(0);
            $table->integer('gizi')->default(0);
            $table->integer('atl_m')->default(0);
            $table->integer('total')->default(0);
            $table->year('tahun')->default(2024);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel rekap tenaga kesehatan per wilayah per tahun
        Schema::create('rekap_tenaga_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->integer('dokter_umum')->default(0);
            $table->integer('dokter_spesialis')->default(0);
            $table->integer('dokter_gigi')->default(0);
            $table->integer('bidan')->default(0);
            $table->integer('perawat')->default(0);
            $table->integer('ahli_gizi')->default(0);
            $table->integer('apoteker')->default(0);
            $table->integer('sanitarian')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();

            $table->unique(['kecamatan_id', 'desa_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_tenaga_kesehatans');
        Schema::dropIfExists('tenaga_kesehatans');
    }
};
