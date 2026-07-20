@extends('layouts.app')

@section('title', 'Analisis Korelasi')
@section('page-title', 'Analisis Korelasi Faktor Kesehatan')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="git-merge" class="w-3.5 h-3.5 text-teal-400"></i>
                Correlation Diagnostic
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Korelasi Faktor Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Mencari tahu seberapa kuat hubungan statistik antara infrastruktur/SDM kesehatan (Puskesmas, Posyandu, Nakes) dengan tingkat prevalensi stunting di kecamatan.
            </p>
        </div>
        
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @if ($status === 'success')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Results -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
                    <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-4 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                        <i data-lucide="calculator" class="w-4 h-4 text-teal-600"></i>
                        Hasil Analisis Statistik
                    </h2>
                    <p class="text-xs font-mono font-bold text-neutral-500 mb-6">
                        Metode: Koefisien Korelasi Pearson (r). Ukuran Sampel: <strong>{{ $sample_size }} Kecamatan</strong>.
                    </p>
                    
                    <div class="space-y-4">
                        @foreach ($results as $var => $stat)
                            @php
                                $isNegative = $stat['correlation'] < 0;
                                $bgColor = $isNegative ? 'bg-emerald-50' : 'bg-red-50';
                                $borderColor = '#171717';
                                $badgeColor = $isNegative ? 'bg-emerald-200 text-emerald-900 border-emerald-600' : 'bg-rose-200 text-rose-900 border-rose-600';
                            @endphp
                            <div class="border-2 border-[#171717] p-5 shadow-[2px_2px_0px_0px_#171717] {{ $bgColor }}">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <h4 class="font-serif font-black text-sm uppercase text-[#171717]">{{ $var }}</h4>
                                        <p class="text-[10px] font-mono font-bold text-neutral-500 mt-0.5">P-Value: {{ $stat['p_value'] }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-mono font-black border-2 border-[#171717] shadow-[1px_1px_0px_0px_#171717] {{ $badgeColor }}">
                                        r = {{ number_format($stat['correlation'], 3) }}
                                    </span>
                                </div>
                                
                                <p class="text-xs font-mono font-bold text-neutral-700 mt-4 leading-relaxed">
                                    <span class="underline">Interpretasi:</span> Korelasi <strong>{{ $stat['interpretasi'] }}</strong>.
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

                <!-- Interpretation Guide -->
                <div class="bg-white p-6 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
                    <h4 class="text-[#171717] font-serif font-black text-xs uppercase tracking-widest mb-3 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-purple-600"></i>
                        Panduan Nilai r
                    </h4>
                    <ul class="text-[11px] space-y-2 font-mono font-bold text-neutral-600">
                        <li class="flex items-start gap-2">
                            <span class="underline text-emerald-700 min-w-[70px]">Negatif (-)</span> 
                            <span>Hubungan terbalik. Variabel pelindung (misal: penambahan faskes menurunkan stunting).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="underline text-red-700 min-w-[70px]">Positif (+)</span> 
                            <span>Hubungan searah. Variabel berisiko.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="underline text-[#171717] min-w-[70px]">Skor r</span> 
                            <span>Dekat dengan 1 atau -1 menunjukkan hubungan linear yang sangat kuat. Dekat dengan 0 tidak ada hubungan.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Side: Scatter Chart -->
            <div class="lg:col-span-7 bg-white p-6 md:p-8 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] flex flex-col">
                <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="scatter-chart" class="w-4 h-4 text-blue-600"></i>
                    Peta Sebaran (Scatter Plot)
                </h2>
                
                <div class="flex-grow border-2 border-[#171717] p-4 bg-[#f8f8f6] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center min-h-[350px]">
                    <canvas id="scatterChart"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="bg-red-100 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] p-6 font-mono">
            <div class="flex items-center gap-3 text-red-800 font-bold mb-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <h3>Gagal Memuat Analisis</h3>
            </div>
            <p class="text-sm text-red-700">{{ $message }}</p>
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
                    backgroundColor: '#0d9488', // Teal accent
                    borderColor: '#171717',
                    borderWidth: 2,
                    pointRadius: 7,
                    pointHoverRadius: 9,
                    pointHoverBackgroundColor: '#ef4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: { family: 'Courier New', weight: 'bold', size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#171717',
                        titleFont: { family: 'Courier New', weight: 'bold' },
                        bodyFont: { family: 'Courier New' },
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
                        grid: { color: '#e5e5d8' },
                        border: { width: 2, color: '#171717' },
                        title: { 
                            display: true, 
                            text: 'KEPADATAN NAKES (PER 1.000 PENDUDUK)', 
                            font: { family: 'Courier New', weight: 'bold', size: 10 },
                            color: '#171717'
                        },
                        ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                    },
                    y: {
                        grid: { color: '#e5e5d8' },
                        border: { width: 2, color: '#171717' },
                        title: { 
                            display: true, 
                            text: 'PREVALENSI STUNTING (%)', 
                            font: { family: 'Courier New', weight: 'bold', size: 10 },
                            color: '#171717'
                        },
                        ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
