@extends('layouts.app')

@section('title', 'Peringatan Dini Penyakit')
@section('page-title', 'Sistem Peringatan Dini Penyakit Menular')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="siren" class="w-3.5 h-3.5"></i>
                Early Warning System
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Deteksi Lonjakan Penyakit</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Membandingkan jumlah kasus penyakit menular tahun <strong>{{ $tahun }}</strong> terhadap rata-rata (baseline) tahun-tahun sebelumnya. Kecamatan dengan lonjakan &ge; 50% ditandai sebagai wilayah waspada untuk prioritas intervensi.
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @php
        $kritis = collect($alerts)->where('level', 'Kritis')->count();
        $tinggi = collect($alerts)->where('level', 'Tinggi')->count();
        $waspada = collect($alerts)->where('level', 'Waspada')->count();
        $tones = [
            'Kritis' => ['bg-red-50 text-red-800', 'text-red-700'],
            'Tinggi' => ['bg-orange-50 text-orange-800', 'text-orange-700'],
            'Waspada' => ['bg-amber-50 text-amber-800', 'text-amber-700'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="md-card p-6 bg-red-50 text-red-800 rounded-[28px]">
            <p class="text-[11px] font-medium uppercase tracking-wider opacity-70">Kritis (&ge;3x)</p>
            <p class="text-4xl mt-1">{{ $kritis }}</p>
        </div>
        <div class="md-card p-6 bg-orange-50 text-orange-800 rounded-[28px]">
            <p class="text-[11px] font-medium uppercase tracking-wider opacity-70">Tinggi (&ge;2x)</p>
            <p class="text-4xl mt-1">{{ $tinggi }}</p>
        </div>
        <div class="md-card p-6 bg-amber-50 text-amber-800 rounded-[28px]">
            <p class="text-[11px] font-medium uppercase tracking-wider opacity-70">Waspada (&ge;1.5x)</p>
            <p class="text-4xl mt-1">{{ $waspada }}</p>
        </div>
    </div>

    <div class="md-card overflow-hidden">
        <div class="flex items-center gap-2 p-6 pb-4">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-md-primary"></i>
            <h2 class="md-card-title">Daftar Peringatan ({{ count($alerts) }})</h2>
        </div>

        @if (count($alerts) > 0)
            <div class="overflow-x-auto">
                <table class="md-table">
                    <thead>
                        <tr class="border-b border-md-outline/20">
                            <th>Kecamatan</th>
                            <th>Penyakit</th>
                            <th class="text-right">Kasus {{ $tahun }}</th>
                            <th class="text-right">Baseline</th>
                            <th class="text-right">Perubahan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-md-outline/10">
                        @foreach ($alerts as $a)
                            @php $tone = $tones[$a['level']] ?? $tones['Waspada']; @endphp
                            <tr class="hover:bg-md-surface-container/60 transition-colors">
                                <td class="font-medium">{{ $a['kecamatan'] }}</td>
                                <td class="text-md-on-surface-variant">{{ $a['penyakit'] }}</td>
                                <td class="text-right">{{ number_format($a['terkini']) }}</td>
                                <td class="text-right text-md-on-surface-variant">{{ number_format($a['baseline'], 1) }}</td>
                                <td class="text-right font-medium {{ $a['pct_change'] >= 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                    {{ $a['pct_change'] > 0 ? '+' : '' }}{{ $a['pct_change'] }}%
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $tone[0] }}">
                                        {{ $a['level'] }} ({{ $a['rasio'] }}x)
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-10 text-center">
                <i data-lucide="shield-check" class="w-10 h-10 text-emerald-600 mx-auto mb-3"></i>
                <p class="text-sm text-md-on-surface-variant">Tidak ada lonjakan penyakit menular signifikan pada tahun {{ $tahun }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection
