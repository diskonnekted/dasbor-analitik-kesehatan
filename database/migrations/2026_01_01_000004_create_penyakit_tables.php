<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Master data penyakit
        Schema::create('penyakits', function (Blueprint $table) {
            $table->id();
            $table->string('kode_icd10', 10)->nullable(); // ICD-10 Code
            $table->string('nama');
            $table->enum('kategori', [
                'Menular', 'Tidak Menular', 'Penyakit Tropis',
                'Gizi', 'Kesehatan Jiwa', 'Lainnya'
            ])->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('wajib_lapor')->default(false);
            $table->timestamps();
        });

        // Data kasus penyakit per tahun (sesuai open data)
        Schema::create('kasus_penyakits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->integer('malaria')->default(0);
            $table->integer('tb_paru')->default(0);
            $table->integer('pneumonia')->default(0);
            $table->integer('kusta')->default(0);
            $table->integer('tetanus_neonatorum')->default(0);
            $table->integer('campak')->default(0);
            $table->integer('diare')->default(0);
            $table->integer('dbd')->default(0);
            $table->integer('hiv_baru')->default(0);
            $table->integer('hiv_kumulatif')->default(0);
            $table->integer('ims')->default(0);
            $table->timestamps();
        });
        
        // Data Pasien Rawat (Jalan/Inap)
        Schema::create('pasien_rawats', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->string('puskesmas');
            $table->integer('rawat_jalan')->default(0);
            $table->integer('rawat_inap')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien_rawats');
        Schema::dropIfExists('kasus_penyakits');
        Schema::dropIfExists('penyakits');
    }
};
