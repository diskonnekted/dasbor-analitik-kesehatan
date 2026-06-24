<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel Posyandu
        Schema::create('posyandus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('jumlah_kader')->default(0);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });

        Schema::create('stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('posyandu_id')->nullable()->constrained()->onDelete('set null');
            $table->year('tahun');
            $table->tinyInteger('bulan')->nullable(); // Jika data bulanan
            $table->integer('jumlah_balita')->default(0);
            $table->integer('jumlah_balita_diukur')->default(0);
            $table->integer('jumlah_stunting')->default(0);
            $table->integer('jumlah_gizi_buruk')->default(0);
            $table->integer('jumlah_gizi_kurang')->default(0);
            $table->integer('jumlah_gizi_lebih')->default(0);
            $table->decimal('prevalensi_stunting', 5, 2)->default(0); // dalam %
            $table->text('intervensi')->nullable();
            $table->timestamps();

            $table->index(['kecamatan_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stuntings');
        Schema::dropIfExists('posyandus');
    }
};
