@extends('layouts.app')

@section('title', 'Pemetaan Klaster')
@section('page-title', 'Pemetaan Klaster Wilayah Stunting')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="grid-3x3" class="w-3.5 h-3.5"></i>
                K-Means Clustering
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Klastering Tingkat Kerawanan</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Mengelompokkan kecamatan secara otomatis berdasarkan kemiripan tingkat prevalensi stunting menggunakan algoritma K-Means Clustering dari Python Service.
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @if ($status === 'success')
        @php
            $levelTone = [
                'Tinggi' => ['bg-red-50 text-red-800', 'text-red-600', 'alert-octagon'],
                'Sedang' => ['bg-amber-50 text-amber-800', 'text-amber-600', 'alert-triangle'],
                'Rendah' => ['bg-emerald-50 text-emerald-800', 'text-emerald-600', 'shield-alert'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach (['Tinggi' => 'Rawan', 'Sedang' => 'Waspada', 'Rendah' => 'Aman'] as $level => $label)
                @php $tone = $levelTone[$level]; @endphp
                <div class="md-card p-6 {{ $tone[0] }} rounded-[28px] text-center flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-white/70 rounded-full flex items-center justify-center mb-3">
                        <i data-lucide="{{ $tone[2] }}" class="w-6 h-6 {{ $tone[1] }}"></i>
                    </div>
                    <h4 class="text-xs font-medium uppercase tracking-wider">{{ $level }} ({{ $label }})</h4>
                    <p class="text-4xl font-normal mt-2">
                        {{ collect($clusters)->where('kerawanan', $level)->count() }}
                    </p>
                    <p class="text-[11px] opacity-70 mt-1">Kecamatan</p>
                </div>
            @endforeach
        </div>

        <div class="md-card p-6">
            <h2 class="md-card-title mb-6">
                <i data-lucide="list" class="w-4 h-4 text-md-primary"></i>
                Daftar Hasil Pembagian Wilayah
            </h2>
            <div class="overflow-x-auto">
                <table class="md-table">
                    <thead>
                        <tr class="border-b border-md-outline/20">
                            <th>Nama Kecamatan</th>
                            <th class="text-center">Prevalensi Stunting</th>
                            <th>Tingkat Kerawanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-md-outline/10">
                        @foreach ($clusters as $cluster)
                            <tr class="hover:bg-md-surface-container/60 transition-colors">
                                <td class="font-medium">{{ $cluster['nama'] }}</td>
                                <td class="text-center">{{ number_format($cluster['stunting'], 2) }}%</td>
                                <td>
                                    @php $tone = $levelTone[$cluster['kerawanan']]; @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $tone[0] }}">
                                        {{ strtoupper($cluster['kerawanan']) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="flex items-start gap-4 rounded-[28px] bg-red-50 p-6 text-red-800">
            <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5"></i>
            <div>
                <h3 class="font-medium mb-1">Gagal Memuat Analisis Klastering</h3>
                <p class="text-sm opacity-80">{{ $message }}</p>
            </div>
        </div>
    @endif
</div>
@endsection
