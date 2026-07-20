@extends('layouts.app')

@section('title', 'Peringatan Dini Penyakit')
@section('page-title', 'Sistem Peringatan Dini Penyakit Menular')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="siren" class="w-3.5 h-3.5 text-red-400"></i>
                Early Warning System
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Deteksi Lonjakan Penyakit</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Membandingkan jumlah kasus penyakit menular tahun <strong>{{ $tahun }}</strong> terhadap rata-rata (baseline) tahun-tahun sebelumnya. Kecamatan dengan lonjakan &ge; 50% ditandai sebagai wilayah waspada untuk prioritas intervensi.
            </p>
        </div>

        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @php
        $kritis = collect($alerts)->where('level', 'Kritis')->count();
        $tinggi = collect($alerts)->where('level', 'Tinggi')->count();
        $waspada = collect($alerts)->where('level', 'Waspada')->count();
    @endphp

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-red-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <p class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-neutral-500">Kritis (&ge;3x)</p>
            <p class="text-4xl font-mono font-black text-red-700 mt-1">{{ $kritis }}</p>
        </div>
        <div class="bg-orange-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <p class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-neutral-500">Tinggi (&ge;2x)</p>
            <p class="text-4xl font-mono font-black text-orange-700 mt-1">{{ $tinggi }}</p>
        </div>
        <div class="bg-amber-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <p class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-neutral-500">Waspada (&ge;1.5x)</p>
            <p class="text-4xl font-mono font-black text-amber-700 mt-1">{{ $waspada }}</p>
        </div>
    </div>

    <!-- Tabel Alert -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
        <div class="bg-[#171717] p-4 flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-400"></i>
            <h2 class="text-sm font-serif font-black uppercase tracking-widest text-white">Daftar Peringatan ({{ count($alerts) }})</h2>
        </div>

        @if (count($alerts) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f1f1ef] border-b-2 border-[#171717] text-[10px] font-mono font-extrabold uppercase tracking-wider text-neutral-600">
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3">Penyakit</th>
                            <th class="px-4 py-3 text-right">Kasus {{ $tahun }}</th>
                            <th class="px-4 py-3 text-right">Baseline</th>
                            <th class="px-4 py-3 text-right">Perubahan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        @foreach ($alerts as $a)
                            @php
                                $badge = match ($a['level']) {
                                    'Kritis' => 'bg-red-200 text-red-900 border-red-600',
                                    'Tinggi' => 'bg-orange-200 text-orange-900 border-orange-600',
                                    default => 'bg-amber-100 text-amber-900 border-amber-500',
                                };
                            @endphp
                            <tr class="border-b border-neutral-200 hover:bg-[#f8f8f6]">
                                <td class="px-4 py-3 font-bold text-[#171717]">{{ $a['kecamatan'] }}</td>
                                <td class="px-4 py-3 text-neutral-700">{{ $a['penyakit'] }}</td>
                                <td class="px-4 py-3 text-right font-bold text-[#171717]">{{ number_format($a['terkini']) }}</td>
                                <td class="px-4 py-3 text-right text-neutral-500">{{ number_format($a['baseline'], 1) }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $a['pct_change'] >= 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                    {{ $a['pct_change'] > 0 ? '+' : '' }}{{ $a['pct_change'] }}%
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider border-2 shadow-[1px_1px_0px_0px_#171717] {{ $badge }}">
                                        {{ $a['level'] }} ({{ $a['rasio'] }}x)
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center font-mono">
                <i data-lucide="shield-check" class="w-10 h-10 text-emerald-600 mx-auto mb-3"></i>
                <p class="text-sm font-bold text-neutral-700">Tidak ada lonjakan penyakit menular signifikan pada tahun {{ $tahun }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection
