<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imunisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('posyandu_id')->nullable()->constrained()->onDelete('set null');
            $table->year('tahun');
            $table->tinyInteger('bulan')->nullable();
            
            // Target
            $table->integer('target_bayi')->default(0);
            $table->integer('target_balita')->default(0);
            $table->integer('target_bumil')->default(0);
            $table->integer('target_anak_sekolah')->default(0);
            
            // Cakupan imunisasi dasar bayi
            $table->integer('bcg')->default(0);
            $table->integer('polio1')->default(0);
            $table->integer('polio2')->default(0);
            $table->integer('polio3')->default(0);
            $table->integer('polio4')->default(0);
            $table->integer('dpt_hb_hib1')->default(0);
            $table->integer('dpt_hb_hib2')->default(0);
            $table->integer('dpt_hb_hib3')->default(0);
            $table->integer('campak_mr1')->default(0);
            $table->integer('campak_mr2')->default(0);
            $table->integer('imunisasi_dasar_lengkap')->default(0);
            
            // Imunisasi tambahan
            $table->integer('tt_bumil')->default(0); // Tetanus Toksoid
            $table->integer('dt_bulanan')->default(0); // Difteri Tetanus
            $table->integer('japanese_encophalitis')->default(0);
            $table->integer('rotavirus')->default(0);
            $table->integer('pc')->default(0); // Pneumococcal Conjugate
            $table->integer('influenza')->default(0);
            $table->integer('hpv')->default(0);
            $table->integer('mr_campak')->default(0); // PIN MR/Campak
            
            // Persentase cakupan
            $table->decimal('persentase_dasar_lengkap', 5, 2)->default(0);
            
            $table->timestamps();

            $table->index(['kecamatan_id', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imunisasis');
    }
};
