@extends('layouts.app')

@section('title', 'Pemetaan Klaster')
@section('page-title', 'Pemetaan Klaster Wilayah Stunting')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="grid-3x3" class="w-3.5 h-3.5 text-purple-400"></i>
                K-Means Clustering
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Klastering Tingkat Kerawanan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Mengelompokkan kecamatan secara otomatis berdasarkan kemiripan tingkat prevalensi stunting menggunakan algoritma K-Means Clustering dari Python Service.
            </p>
        </div>
        
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @if ($status === 'success')
        <!-- Summary Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Tinggi -->
            <div class="bg-red-200 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] text-center flex flex-col items-center justify-center">
                <div class="w-10 h-10 border-2 border-[#171717] bg-white rounded-none flex items-center justify-center mb-3 shadow-[2px_2px_0px_0px_#171717]">
                    <i data-lucide="alert-octagon" class="w-6 h-6 text-red-600"></i>
                </div>
                <h4 class="font-serif font-black text-xs uppercase text-[#171717] tracking-wider">Tinggi (Rawan)</h4>
                <p class="text-4xl font-mono font-black text-[#171717] mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Tinggi')->count() }}
                </p>
                <p class="text-[9px] font-mono font-bold text-neutral-600 uppercase tracking-wider mt-1">Kecamatan Terpengaruh</p>
            </div>
            
            <!-- Sedang -->
            <div class="bg-amber-200 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] text-center flex flex-col items-center justify-center">
                <div class="w-10 h-10 border-2 border-[#171717] bg-white rounded-none flex items-center justify-center mb-3 shadow-[2px_2px_0px_0px_#171717]">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-amber-600"></i>
                </div>
                <h4 class="font-serif font-black text-xs uppercase text-[#171717] tracking-wider">Sedang (Waspada)</h4>
                <p class="text-4xl font-mono font-black text-[#171717] mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Sedang')->count() }}
                </p>
                <p class="text-[9px] font-mono font-bold text-neutral-600 uppercase tracking-wider mt-1">Kecamatan Terpengaruh</p>
            </div>
            
            <!-- Rendah -->
            <div class="bg-emerald-200 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] text-center flex flex-col items-center justify-center">
                <div class="w-10 h-10 border-2 border-[#171717] bg-white rounded-none flex items-center justify-center mb-3 shadow-[2px_2px_0px_0px_#171717]">
                    <i data-lucide="shield-alert" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <h4 class="font-serif font-black text-xs uppercase text-[#171717] tracking-wider">Rendah (Aman)</h4>
                <p class="text-4xl font-mono font-black text-[#171717] mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Rendah')->count() }}
                </p>
                <p class="text-[9px] font-mono font-bold text-neutral-600 uppercase tracking-wider mt-1">Kecamatan Terpengaruh</p>
            </div>
        </div>

        <!-- Clustering Table Container -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
            <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="list" class="w-4 h-4 text-purple-600"></i>
                Daftar Hasil Pembagian Wilayah
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border-2 border-[#171717] text-left text-xs font-mono font-bold">
                    <thead>
                        <tr class="bg-[#f4f4f0] border-b-2 border-[#171717]">
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider">Nama Kecamatan</th>
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">Prevalensi Stunting</th>
                            <th class="px-6 py-4 uppercase tracking-wider">Tingkat Kerawanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-[#171717]">
                        @foreach ($clusters as $cluster)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 border-r-2 border-[#171717] uppercase">{{ $cluster['nama'] }}</td>
                                <td class="px-6 py-4 border-r-2 border-[#171717] text-center font-black text-sm">{{ number_format($cluster['stunting'], 2) }}%</td>
                                <td class="px-6 py-4">
                                    @if ($cluster['kerawanan'] == 'Tinggi')
                                        <span class="inline-block px-3 py-1 bg-red-200 border-2 border-[#171717] text-red-950 font-black shadow-[1px_1px_0px_0px_#171717]">
                                            TINGGI
                                        </span>
                                    @elseif ($cluster['kerawanan'] == 'Sedang')
                                        <span class="inline-block px-3 py-1 bg-amber-200 border-2 border-[#171717] text-amber-950 font-black shadow-[1px_1px_0px_0px_#171717]">
                                            SEDANG
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-emerald-200 border-2 border-[#171717] text-emerald-950 font-black shadow-[1px_1px_0px_0px_#171717]">
                                            RENDAH
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-red-100 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] p-6 font-mono">
            <div class="flex items-center gap-3 text-red-800 font-bold mb-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <h3>Gagal Memuat Analisis Klastering</h3>
            </div>
            <p class="text-sm text-red-700">{{ $message }}</p>
        </div>
    @endif
</div>
@endsection
