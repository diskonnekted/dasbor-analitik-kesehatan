<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faskes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->enum('jenis', [
                'RSUD', 'RS Swasta', 'Puskesmas', 'Puskesmas Pembantu',
                'Polindes', 'Klinik', 'Praktik Dokter', 'Praktik Bidan',
                'Apotek', 'Laboratorium'
            ])->nullable();
            $table->enum('tipe', ['A', 'B', 'C', 'D', 'Utama', 'Pratama'])->nullable();
            $table->string('alamat')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('kepala')->nullable(); // Nama kepala/pimpinan
            $table->integer('jumlah_tempat_tidur')->default(0); // Untuk RS/Puskesmas
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->year('tahun_berdiri')->nullable();
            $table->text('layanan')->nullable(); // JSON array of services
            
            // Kolom untuk sinkronisasi OpenData (Agregat)
            $table->year('tahun')->nullable();
            $table->integer('rs_umum')->default(0);
            $table->integer('puskesmas')->default(0);
            $table->integer('klinik')->default(0);
            $table->integer('posyandu')->default(0);
            $table->integer('poskesdes')->default(0);
            $table->integer('total')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};
