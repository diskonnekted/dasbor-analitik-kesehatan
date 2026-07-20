@extends('layouts.app')

@section('title', 'Menu Analisis Lanjutan')
@section('page-title', 'Menu Analisis Lanjutan')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="line-chart" class="w-3.5 h-3.5 text-red-400"></i>
                Advanced Data Analytics
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Pusat Analisis & Evaluasi</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Peralatan analisis kesehatan tingkat lanjut: Temukan pola tersembunyi, prediksi tren masa depan, dan lakukan klasterisasi spasial data prevalensi stunting di wilayah Kabupaten Banjarnegara menggunakan Python Analytics Engine.
            </p>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Korelasi -->
        <a href="{{ route('analisis.korelasi') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-teal-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:rotate-3 transition-transform">
                <i data-lucide="git-merge" class="w-8 h-8 text-teal-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Analisis Korelasi</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Analisis hubungan statistik antar variabel kesehatan, seperti korelasi jumlah Nakes atau Faskes dengan prevalensi stunting.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-teal-700 group-hover:underline">
                <span>Buka Korelasi</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Klastering -->
        <a href="{{ route('analisis.klaster') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-purple-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:-rotate-3 transition-transform">
                <i data-lucide="grid-3x3" class="w-8 h-8 text-purple-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Pemetaan Klaster</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Pengelompokan wilayah kecamatan berdasarkan tingkat kerawanan dan kemiripan data gizi buruk serta sarana kesehatan.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-purple-700 group-hover:underline">
                <span>Buka Klaster</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Prediksi -->
        <a href="{{ route('analisis.prediksi') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-amber-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:rotate-6 transition-transform">
                <i data-lucide="trending-up" class="w-8 h-8 text-amber-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Prediksi Tren</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Peramalan arah perkembangan prevalensi stunting 3 tahun ke depan menggunakan regresi linear time-series.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-amber-700 group-hover:underline">
                <span>Buka Prediksi</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Spasial -->
        <a href="{{ route('analisis.spasial') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-emerald-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:-rotate-6 transition-transform">
                <i data-lucide="map" class="w-8 h-8 text-emerald-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Analisis Spasial</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Visualisasi data kesehatan secara geografis interaktif (GIS) untuk memetakan sebaran kasus stunting per kecamatan.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-emerald-700 group-hover:underline">
                <span>Buka Peta</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Early Warning -->
        <a href="{{ route('analisis.early-warning') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:rotate-3 transition-transform">
                <i data-lucide="siren" class="w-8 h-8 text-red-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Peringatan Dini</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Deteksi lonjakan (outbreak) penyakit menular per kecamatan dengan membandingkan kasus terkini terhadap baseline tahun-tahun sebelumnya.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-red-700 group-hover:underline">
                <span>Buka Peringatan</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Rasio Nakes -->
        <a href="{{ route('analisis.rasio-nakes') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-blue-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:-rotate-3 transition-transform">
                <i data-lucide="stethoscope" class="w-8 h-8 text-blue-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Rasio Nakes</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Perbandingan beban penyakit menular terhadap jumlah tenaga kesehatan tiap kecamatan untuk mengidentifikasi wilayah kekurangan SDM.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-blue-700 group-hover:underline">
                <span>Buka Rasio</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- Indeks Kerawanan -->
        <a href="{{ route('analisis.indeks-kerawanan') }}" 
           class="group block bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all text-center">
            <div class="w-16 h-16 mx-auto bg-orange-100 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center mb-5 group-hover:rotate-6 transition-transform">
                <i data-lucide="shield-alert" class="w-8 h-8 text-orange-700"></i>
            </div>
            <h3 class="text-lg font-serif font-black uppercase text-[#171717] mb-2 group-hover:underline">Indeks Kerawanan</h3>
            <p class="text-xs font-mono font-bold text-neutral-500 leading-relaxed">
                Skor gabungan (stunting, beban penyakit per nakes, kelangkaan faskes) untuk menentukan prioritas intervensi tiap kecamatan.
            </p>
            <div class="mt-4 inline-flex items-center gap-1 text-[10px] font-mono font-bold uppercase text-orange-700 group-hover:underline">
                <span>Buka Indeks</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>
    </div>
</div>
@endsection
