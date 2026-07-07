@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Pusat Laporan Kesehatan')

@section('content')
<div class="space-y-8">
    <!-- Top Hero Banner (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-neutral-400"></i>
                Reporting Center
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Pusat Unduhan Laporan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Hasilkan lembar rekapitulasi data kesehatan Kabupaten Banjarnegara dalam format interaktif (HTML), tabulasi (Excel), maupun dokumen cetak (PDF).
            </p>
        </div>
    </div>

    <!-- Reports Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card: Laporan Stunting -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_#171717] transition-all">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-6 bg-amber-400 border border-[#171717]"></span>
                    <h4 class="font-serif font-black text-lg text-gray-900 uppercase">Laporan Stunting Tahunan</h4>
                </div>
                <p class="text-xs font-mono font-bold text-neutral-500 mb-6 leading-relaxed">Rekapitulasi data prevalensi stunting dari seluruh kecamatan untuk tahun berjalan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('laporan.generate', ['type' => 'stunting']) }}" class="px-4 py-2 border-2 border-[#171717] bg-blue-100 hover:bg-blue-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Lihat HTML</a>
                <a href="{{ route('laporan.export.excel', ['type' => 'stunting']) }}" class="px-4 py-2 border-2 border-[#171717] bg-emerald-100 hover:bg-emerald-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Export Excel</a>
                <a href="{{ route('laporan.export.pdf', ['type' => 'stunting']) }}" target="_blank" class="px-4 py-2 border-2 border-[#171717] bg-rose-100 hover:bg-rose-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Cetak PDF</a>
            </div>
        </div>
        
        <!-- Card: Laporan Penyakit -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_#171717] transition-all">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-6 bg-red-400 border border-[#171717]"></span>
                    <h4 class="font-serif font-black text-lg text-gray-900 uppercase">Laporan Wabah & Penyakit</h4>
                </div>
                <p class="text-xs font-mono font-bold text-neutral-500 mb-6 leading-relaxed">Rincian laporan persebaran 10 jenis penyakit terbanyak (Malaria, TB, Diare, dll).</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('laporan.generate', ['type' => 'penyakit']) }}" class="px-4 py-2 border-2 border-[#171717] bg-blue-100 hover:bg-blue-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Lihat HTML</a>
                <a href="{{ route('laporan.export.excel', ['type' => 'penyakit']) }}" class="px-4 py-2 border-2 border-[#171717] bg-emerald-100 hover:bg-emerald-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Export Excel</a>
                <a href="{{ route('laporan.export.pdf', ['type' => 'penyakit']) }}" target="_blank" class="px-4 py-2 border-2 border-[#171717] bg-rose-100 hover:bg-rose-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Cetak PDF</a>
            </div>
        </div>
        
        <!-- Card: Laporan Fasilitas Kesehatan -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_#171717] transition-all">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-6 bg-teal-400 border border-[#171717]"></span>
                    <h4 class="font-serif font-black text-lg text-gray-900 uppercase">Laporan Fasilitas Kesehatan</h4>
                </div>
                <p class="text-xs font-mono font-bold text-neutral-500 mb-6 leading-relaxed">Daftar lengkap Puskesmas, Rumah Sakit, dan Klinik per kecamatan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('laporan.generate', ['type' => 'faskes']) }}" class="px-4 py-2 border-2 border-[#171717] bg-blue-100 hover:bg-blue-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Lihat HTML</a>
                <a href="{{ route('laporan.export.excel', ['type' => 'faskes']) }}" class="px-4 py-2 border-2 border-[#171717] bg-emerald-100 hover:bg-emerald-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Export Excel</a>
                <a href="{{ route('laporan.export.pdf', ['type' => 'faskes']) }}" target="_blank" class="px-4 py-2 border-2 border-[#171717] bg-rose-100 hover:bg-rose-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Cetak PDF</a>
            </div>
        </div>

        <!-- Card: Laporan Tenaga Kesehatan -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col justify-between hover:shadow-[6px_6px_0px_0px_#171717] transition-all">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-6 bg-cyan-400 border border-[#171717]"></span>
                    <h4 class="font-serif font-black text-lg text-gray-900 uppercase">Laporan Tenaga Kesehatan</h4>
                </div>
                <p class="text-xs font-mono font-bold text-neutral-500 mb-6 leading-relaxed">Jumlah ketersediaan Dokter, Perawat, dan Bidan di berbagai jenjang puskesmas.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('laporan.generate', ['type' => 'nakes']) }}" class="px-4 py-2 border-2 border-[#171717] bg-blue-100 hover:bg-blue-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Lihat HTML</a>
                <a href="{{ route('laporan.export.excel', ['type' => 'nakes']) }}" class="px-4 py-2 border-2 border-[#171717] bg-emerald-100 hover:bg-emerald-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Export Excel</a>
                <a href="{{ route('laporan.export.pdf', ['type' => 'nakes']) }}" target="_blank" class="px-4 py-2 border-2 border-[#171717] bg-rose-100 hover:bg-rose-200 font-mono font-black text-xs uppercase shadow-[2px_2px_0px_0px_#171717] transition-all">Cetak PDF</a>
            </div>
        </div>
    </div>
</div>
@endsection
