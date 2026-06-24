<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel Kecamatan
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('jumlah_kk')->default(0);
            $table->decimal('luas_wilayah', 10, 2)->nullable(); // dalam km²
            $table->timestamps();
        });

        // Tabel Desa/Kelurahan
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->string('kode', 13)->unique();
            $table->string('nama');
            $table->string('jenis'); // Desa/Kelurahan
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('jumlah_kk')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desas');
        Schema::dropIfExists('kecamatans');
    }
};
