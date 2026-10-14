@extends('layouts.app')

@section('title', 'Analisis Korelasi')
@section('page-title', 'Analisis Korelasi Faktor Kesehatan')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[32px] bg-md-surface-container p-8">
        <div class="pointer-events-none absolute -top-20 -right-16 h-64 w-64 rounded-full bg-md-primary/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="max-w-3xl">
                <span class="md-badge">
                    <i data-lucide="git-merge" class="h-3.5 w-3.5 text-md-tertiary" aria-hidden="true"></i>
                    Correlation Diagnostic
                </span>
                <h1 class="mt-4 text-3xl font-medium tracking-tight text-md-on-surface">Korelasi Faktor Kesehatan</h1>
                <p class="mt-3 text-sm leading-relaxed text-md-on-surface-variant">
                    Mencari tahu seberapa kuat hubungan statistik antara infrastruktur/SDM kesehatan (Puskesmas, Posyandu, Nakes) dengan tingkat prevalensi stunting di kecamatan.
                </p>
            </div>
            <a href="{{ route('analisis.index') }}"
               class="inline-flex h-11 shrink-0 items-center gap-2 self-start rounded-full bg-md-secondary-container px-6 text-sm font-medium text-md-on-secondary-container transition-all duration-300 ease-md hover:bg-md-secondary-container/70 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2 md:self-auto">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali ke Menu
            </a>
        </div>
    </div>

    @if ($status === 'success')
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 md:gap-8">
            <!-- Kiri: Hasil analisis -->
            <div class="space-y-6 lg:col-span-5">
                <div class="md-card p-6">
                    <h2 class="md-card-title mb-4 border-b border-md-outline/15 pb-3">
                        <i data-lucide="calculator" class="h-4.5 w-4.5 text-md-tertiary"></i>
                        Hasil Analisis Statistik
                    </h2>
                    <p class="mb-6 text-xs text-md-on-surface-variant">
                        Metode: Koefisien Korelasi Pearson (r). Ukuran Sampel: <strong>{{ $sample_size }} Kecamatan</strong>.
                    </p>

                    <div class="space-y-4">
                        @foreach ($results as $var => $stat)
                            @php
                                $isNegative = $stat['correlation'] < 0;
                                $tonal = $isNegative ? 'bg-emerald-50' : 'bg-rose-50';
                                $badge = $isNegative ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800';
                            @endphp
                            <div class="rounded-[24px] p-5 {{ $tonal }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-md-on-surface">{{ $var }}</h4>
                                        <p class="mt-0.5 text-xs text-md-on-surface-variant">P-Value: {{ $stat['p_value'] }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $badge }}">
                                        r = {{ number_format($stat['correlation'], 3) }}
                                    </span>
                                </div>

                                <p class="mt-4 text-xs leading-relaxed text-md-on-surface-variant">
                                    <span class="font-medium text-md-on-surface">Interpretasi:</span> Korelasi <strong>{{ $stat['interpretasi'] }}</strong>.
                                    @if ($isNegative)
                                        Peningkatan jumlah {{ $var }} berasosiasi dengan penurunan prevalensi stunting secara signifikan.
                                    @else
                                        Terdapat hubungan searah (korelasi positif) antara {{ $var }} dengan angka stunting.
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Panduan -->
                <div class="md-insight">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-md-primary/15 text-md-primary">
                        <i data-lucide="help-circle" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h4 class="mb-2 text-sm font-medium">Panduan Nilai r</h4>
                        <ul class="space-y-2 text-xs leading-relaxed text-md-on-secondary-container/80">
                            <li><span class="font-medium text-md-on-secondary-container">Negatif (–)</span> — hubungan terbalik; variabel pelindung (misal penambahan faskes menurunkan stunting).</li>
                            <li><span class="font-medium text-md-on-secondary-container">Positif (+)</span> — hubungan searah; variabel berisiko.</li>
                            <li><span class="font-medium text-md-on-secondary-container">Skor r</span> — dekat ±1 berarti hubungan linear sangat kuat, dekat 0 berarti tidak ada hubungan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kanan: Scatter chart -->
            <div class="md-card flex flex-col p-6 md:p-8 lg:col-span-7">
                <h2 class="md-card-title mb-6 border-b border-md-outline/15 pb-3">
                    <i data-lucide="scatter-chart" class="h-4.5 w-4.5 text-md-primary"></i>
                    Peta Sebaran (Scatter Plot)
                </h2>

                <div class="flex min-h-[350px] flex-grow items-center justify-center rounded-[24px] bg-md-surface-container-low p-4 shadow-inner">
                    <canvas id="scatterChart" role="img" aria-label="Scatter plot kepadatan nakes terhadap prevalensi stunting"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-start gap-4 rounded-[28px] bg-rose-50 p-6">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                <i data-lucide="alert-triangle" class="h-5 w-5" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-rose-900">Gagal Memuat Analisis</h3>
                <p class="mt-1 text-sm text-rose-700">{{ $message }}</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if ($status === 'success')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const data = @json($data);

        // Scatter Plot Kepadatan Nakes (per 1.000 penduduk) vs Stunting
        const scatterData = data.map(item => ({
            x: item.nakes,
            y: item.stunting,
            kecamatan: item.nama,
            nakesAbsolut: item.nakes_absolut,
            penduduk: item.penduduk
        }));

        const ctx = document.getElementById('scatterChart').getContext('2d');
        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Tenaga Medis vs Stunting',
                    data: scatterData,
                    backgroundColor: 'rgba(103, 80, 164, 0.65)',
                    borderColor: '#6750A4',
                    borderWidth: 1.5,
                    pointRadius: 7,
                    pointHoverRadius: 9,
                    pointHoverBackgroundColor: '#7D5260'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { boxWidth: 10, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: '#49454F',
                        titleFont: { weight: '500' },
                        cornerRadius: 12,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const point = context.raw;
                                return ` Kecamatan ${point.kecamatan} | Nakes/1000: ${point.x} (${point.nakesAbsolut} nakes) | Stunting: ${point.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(121,116,126,0.2)' },
                        border: { width: 0 },
                        title: {
                            display: true,
                            text: 'KEPADATAN NAKES (PER 1.000 PENDUDUK)',
                            font: { size: 10, weight: '500' },
                            color: '#49454F'
                        }
                    },
                    y: {
                        grid: { color: 'rgba(121,116,126,0.2)' },
                        border: { width: 0 },
                        title: {
                            display: true,
                            text: 'PREVALENSI STUNTING (%)',
                            font: { size: 10, weight: '500' },
                            color: '#49454F'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
