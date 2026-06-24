<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Laporan bulanan
        Schema::create('laporan_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan');
            
            // Indikator utama
            $table->integer('jumlah_kunjungan_puskesmas')->default(0);
            $table->integer('jumlah_kunjungan_rs')->default(0);
            $table->integer('jumlah_peserta_bpjs')->default(0);
            $table->integer('jumlah_penduduk')->default(0);
            $table->decimal('rasio_dokter_penduduk', 10, 4)->default(0);
            $table->decimal('rasio_bidan_penduduk', 10, 4)->default(0);
            
            // Status kesehatan
            $table->enum('status_gizi_buruk', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->enum('status_stunting', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->enum('status_wabah', ['Normal', 'Waspada', 'Siaga', 'Darurat'])->default('Normal');
            
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kecamatan_id', 'desa_id', 'tahun', 'bulan']);
        });

        // Tabel hasil analisa
        Schema::create('hasil_analisas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_analisa'); // 'stunting', 'aki_akb', 'penyakit', dll
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan')->nullable();
            $table->json('hasil'); // JSON hasil analisa
            $table->json('rekomendasi')->nullable(); // JSON rekomendasi
            $table->decimal('skor_risiko', 5, 2)->nullable(); // 0-100
            $table->enum('level_risiko', ['Rendah', 'Sedang', 'Tinggi', 'Kritis'])->nullable();
            $table->timestamps();
        });

        // Tabel alert/peringatan
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // 'wabah', 'stunting_tinggi', 'aki_meningkat', dll
            $table->foreignId('kecamatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('level', ['Info', 'Warning', 'Danger', 'Critical'])->default('Info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('tanggal_kejadian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('hasil_analisas');
        Schema::dropIfExists('laporan_bulanans');
    }
};
