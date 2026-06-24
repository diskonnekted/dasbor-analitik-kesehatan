<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Angka Kematian Ibu
        Schema::create('akis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->string('puskesmas')->nullable(); // Ditambahkan dari data.md
            $table->integer('jumlah_kelahiran_hidup')->default(0);
            $table->integer('jumlah_kematian_ibu')->default(0);
            $table->decimal('aki_per_100ribu', 10, 2)->default(0); // per 100.000 kelahiran hidup
            $table->enum('penyebab', [
                'Pendarahan', 'Eklampsia', 'Infeksi', 'Partus Lama',
                'Abortus', 'Lainnya', 'Tidak Diketahui'
            ])->nullable();
            $table->integer('usia_ibu')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Angka Kematian Bayi
        Schema::create('akbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->string('puskesmas')->nullable(); // Ditambahkan dari data.md
            $table->integer('jumlah_kelahiran_hidup')->default(0);
            $table->integer('jumlah_kematian_bayi')->default(0); // 0-11 bulan
            $table->integer('jumlah_kematian_neonatal')->default(0); // 0-28 hari
            $table->integer('jumlah_lahir_mati')->default(0);
            $table->decimal('akb_per_1000', 10, 2)->default(0); // per 1.000 kelahiran hidup
            $table->enum('penyebab', [
                'Prematur', 'BBLR', 'Asfiksia', 'Infeksi',
                'Kelainan Kongenital', 'Lainnya', 'Tidak Diketahui'
            ])->nullable();
            $table->integer('berat_badan_lahir')->nullable(); // dalam gram
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
        
        // Persalinan
        Schema::create('persalinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->string('puskesmas')->nullable();
            $table->integer('tenaga_kesehatan')->default(0);
            $table->integer('dukun')->default(0);
            $table->integer('sendiri')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persalinans');
        Schema::dropIfExists('akbs');
        Schema::dropIfExists('akis');
    }
};
