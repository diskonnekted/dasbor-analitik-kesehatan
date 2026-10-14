@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Pusat Laporan Kesehatan')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[32px] bg-md-surface-container p-8">
        <div class="pointer-events-none absolute -top-24 -right-16 h-72 w-72 rounded-full bg-md-primary/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative max-w-3xl">
            <span class="md-badge">
                <i data-lucide="file-text" class="h-3.5 w-3.5" aria-hidden="true"></i>
                Reporting Center
            </span>
            <h1 class="mt-4 text-3xl font-medium tracking-tight text-md-on-surface">Pusat Unduhan Laporan</h1>
            <p class="mt-2 text-sm leading-relaxed text-md-on-surface-variant">
                Hasilkan lembar rekapitulasi data kesehatan Kabupaten Banjarnegara dalam format interaktif (HTML), tabulasi (Excel), maupun dokumen cetak (PDF).
            </p>
        </div>
    </div>

    <!-- Grid kartu laporan -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8">
        @foreach ([
            ['type' => 'stunting', 'dot' => 'bg-md-primary', 'icon' => 'trending-up', 'title' => 'Laporan Stunting Tahunan', 'desc' => 'Rekapitulasi data prevalensi stunting dari seluruh kecamatan untuk tahun berjalan.'],
            ['type' => 'penyakit', 'dot' => 'bg-md-tertiary', 'icon' => 'virus', 'title' => 'Laporan Wabah & Penyakit', 'desc' => 'Rincian laporan persebaran 10 jenis penyakit terbanyak (Malaria, TB, Diare, dll).'],
            ['type' => 'faskes', 'dot' => 'bg-md-tertiary', 'icon' => 'building-2', 'title' => 'Laporan Fasilitas Kesehatan', 'desc' => 'Daftar lengkap Puskesmas, Rumah Sakit, dan Klinik per kecamatan.'],
            ['type' => 'nakes', 'dot' => 'bg-md-primary', 'icon' => 'users', 'title' => 'Laporan Tenaga Kesehatan', 'desc' => 'Jumlah ketersediaan Dokter, Perawat, dan Bidan di berbagai jenjang puskesmas.'],
        ] as $rep)
            <div class="md-card group flex flex-col justify-between p-6 transition-all duration-300 ease-md hover:-translate-y-1">
                <div>
                    <div class="mb-3 flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full {{ $rep['dot'] }}/15 text-md-on-surface">
                            <i data-lucide="{{ $rep['icon'] }}" class="h-4.5 w-4.5 {{ $rep['dot'] == 'bg-md-primary' ? 'text-md-primary' : 'text-md-tertiary' }}"></i>
                        </span>
                        <h4 class="text-lg font-medium text-md-on-surface">{{ $rep['title'] }}</h4>
                    </div>
                    <p class="mb-6 text-xs leading-relaxed text-md-on-surface-variant">{{ $rep['desc'] }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('laporan.generate', ['type' => $rep['type']]) }}"
                       class="inline-flex h-9 items-center gap-2 rounded-full bg-md-primary px-5 text-xs font-medium tracking-wide text-md-on-primary transition-all duration-300 ease-md hover:bg-md-primary/90 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                        <i data-lucide="eye" class="h-3.5 w-3.5"></i> Lihat HTML
                    </a>
                    <a href="{{ route('laporan.export.excel', ['type' => $rep['type']]) }}"
                       class="inline-flex h-9 items-center gap-2 rounded-full bg-md-secondary-container px-5 text-xs font-medium tracking-wide text-md-on-secondary-container transition-all duration-300 ease-md hover:bg-md-secondary-container/70 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                        <i data-lucide="file-spreadsheet" class="h-3.5 w-3.5"></i> Export Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', ['type' => $rep['type']]) }}" target="_blank"
                       class="inline-flex h-9 items-center gap-2 rounded-full border border-md-outline/40 px-5 text-xs font-medium tracking-wide text-md-primary transition-all duration-300 ease-md hover:bg-md-primary/5 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                        <i data-lucide="printer" class="h-3.5 w-3.5"></i> Cetak PDF
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
