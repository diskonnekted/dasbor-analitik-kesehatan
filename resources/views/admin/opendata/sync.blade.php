<!-- resources/views/admin/opendata/sync.blade.php -->
@extends('layouts.app')

@section('title', 'Sync OpenData')
@section('page-title', 'Sync Data dari OpenData Banjarnegara')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="md-card p-6 md:p-8">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-md-on-surface">Sync Data Otomatis</h3>
            <p class="mt-2 text-sm leading-relaxed text-md-on-surface-variant">
                Ambil data terbaru dari OpenData Banjarnegara dan import ke database aplikasi.
                Data akan di-sync otomatis setiap hari jam 02:00 WIB.
            </p>
        </div>

        <form action="{{ route('admin.opendata.sync') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="dataset" class="mb-2 block text-sm font-medium text-md-on-surface">
                    Pilih Dataset (Opsional)
                </label>
                <select name="dataset" id="dataset"
                        class="h-14 w-full rounded-t-xl border-b-2 border-md-outline bg-md-surface-container-low px-4 text-sm text-md-on-surface transition-colors duration-200 focus:border-md-primary focus:outline-none focus:ring-2 focus:ring-md-primary/30 focus:ring-offset-0">
                    <option value="">-- Sync Semua Dataset --</option>
                    <option value="tenaga_kesehatan">Jumlah Tenaga Kesehatan</option>
                    <option value="faskes">Jumlah Faskes (RS, Puskesmas, dll)</option>
                    <option value="kasus_penyakit">Jumlah Kasus Penyakit</option>
                    <option value="kematian_ibu_bayi">Kematian Ibu dan Bayi</option>
                    <option value="persalinan">Persalinan menurut Penolong</option>
                    <option value="pasien_rawat">Pasien Rawat Jalan/Inap</option>
                </select>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit"
                        class="inline-flex h-11 items-center gap-2 rounded-full bg-md-primary px-7 text-sm font-medium text-md-on-primary transition-all duration-300 ease-md hover:bg-md-primary/90 hover:shadow-md active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Sync Sekarang
                </button>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex h-11 items-center rounded-full bg-md-secondary-container px-7 text-sm font-medium text-md-on-secondary-container transition-all duration-300 ease-md hover:bg-md-secondary-container/70 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                    Batal
                </a>
            </div>
        </form>

        <div class="mt-8 border-t border-md-outline/15 pt-6">
            <h4 class="mb-3 text-base font-medium text-md-on-surface">Informasi Dataset</h4>
            <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                <div class="rounded-2xl bg-md-surface-container-low px-4 py-3">
                    <dt class="text-xs text-md-on-surface-variant">Sumber</dt>
                    <dd class="mt-0.5 font-medium text-md-on-surface">OpenData Banjarnegara (Dinas Kesehatan)</dd>
                </div>
                <div class="rounded-2xl bg-md-surface-container-low px-4 py-3">
                    <dt class="text-xs text-md-on-surface-variant">Update Terakhir</dt>
                    <dd class="mt-0.5 font-medium text-md-on-surface">{{ now()->format('d F Y H:i') }} WIB</dd>
                </div>
                <div class="rounded-2xl bg-md-surface-container-low px-4 py-3">
                    <dt class="text-xs text-md-on-surface-variant">Frekuensi Sync</dt>
                    <dd class="mt-0.5 font-medium text-md-on-surface">Harian (02:00 WIB) &amp; Mingguan (Senin, 03:00 WIB)</dd>
                </div>
                <div class="rounded-2xl bg-md-surface-container-low px-4 py-3">
                    <dt class="text-xs text-md-on-surface-variant">Total Dataset</dt>
                    <dd class="mt-0.5 font-medium text-md-on-surface">8 dataset kesehatan</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
