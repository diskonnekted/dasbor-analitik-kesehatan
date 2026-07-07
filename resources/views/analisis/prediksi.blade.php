@extends('layouts.app')

@section('title', 'Prediksi Stunting')
@section('page-title', 'Peramalan Tren Kasus Stunting')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="trending-up" class="w-3.5 h-3.5 text-amber-400"></i>
                Time-Series Forecast
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Peramalan Kasus Stunting</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Estimasi perkembangan angka stunting untuk 3 tahun ke depan berdasarkan analisis tren historis wilayah menggunakan model Linear Regression dari Python Service.
            </p>
        </div>
        
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @if(($status ?? 'error') == 'error')
        <div class="bg-red-100 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] p-6 font-mono">
            <div class="flex items-center gap-3 text-red-800 font-bold mb-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <h3>Gagal Memuat Analisis Prediksi</h3>
            </div>
            <p class="text-sm text-red-700">{{ $message ?? 'Service analitik tidak berjalan atau tidak dapat dijangkau.' }}</p>
        </div>
    @else
        <!-- Grid: Trend Card + Chart Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Summary Card -->
            <div class="lg:col-span-4 bg-blue-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-mono font-black text-blue-900 uppercase tracking-widest mb-1">Arah Tren Data</h4>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-4xl font-serif font-black text-blue-950 uppercase">{{ $trend }}</span>
                        <span class="text-xs font-mono font-black text-blue-800 bg-white border-2 border-[#171717] px-2 py-0.5 shadow-[1px_1px_0px_0px_#171717]">
                            Slope: {{ $slope }}
                        </span>
                    </div>
                    <p class="mt-4 text-xs font-mono font-bold text-blue-800 leading-relaxed">
                        Arah slope negatif menunjukkan penurunan prevalensi stunting, sedangkan positif menunjukkan potensi peningkatan jika tidak ada intervensi tambahan.
                    </p>
                </div>
                
                <div class="border-t-2 border-dashed border-blue-400 pt-6 mt-6">
                    <span class="text-[9px] font-mono font-black text-blue-900 uppercase tracking-wider block">Metrik Input</span>
                    <span class="text-xs font-mono font-bold text-blue-800 block mt-1">Data Input: {{ count($historical) }} Tahun Terakhir</span>
                </div>
            </div>

            <!-- Right Side: Projection Chart -->
            <div class="lg:col-span-8 bg-white p-6 md:p-8 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] flex flex-col">
                <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="line-chart" class="w-4 h-4 text-amber-600"></i>
                    Grafik Prediksi Tren (%)
                </h2>
                
                <div class="flex-grow border-2 border-[#171717] p-4 bg-[#f8f8f6] shadow-[2px_2px_0px_0px_#171717] min-h-[300px]">
                    <canvas id="predictionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Prediction Table -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
            <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="table" class="w-4 h-4 text-blue-600"></i>
                Detail Nilai Proyeksi 3 Tahun
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border-2 border-[#171717] text-left text-xs font-mono font-bold">
                    <thead>
                        <tr class="bg-[#f4f4f0] border-b-2 border-[#171717]">
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider">Status Data</th>
                            <th class="px-6 py-4 uppercase tracking-wider">Prevalensi Stunting</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-[#171717]">
                        @foreach($historical as $h)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 border-r-2 border-[#171717]">{{ $h['tahun'] }}</td>
                                <td class="px-6 py-4 border-r-2 border-[#171717]">
                                    <span class="inline-block px-2 py-0.5 bg-neutral-200 border border-[#171717] text-neutral-800 text-[10px]">
                                        HISTORIS
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-[#171717] text-sm">{{ number_format($h['stunting'], 2) }}%</td>
                            </tr>
                        @endforeach
                        @foreach($forecast as $f)
                            <tr class="bg-amber-100 hover:bg-amber-200 transition-colors">
                                <td class="px-6 py-4 border-r-2 border-[#171717] font-black text-amber-950">{{ $f['tahun'] }}</td>
                                <td class="px-6 py-4 border-r-2 border-[#171717]">
                                    <span class="inline-block px-2 py-0.5 bg-amber-300 border-2 border-[#171717] text-amber-950 text-[10px] font-black shadow-[1px_1px_0px_0px_#171717]">
                                        PREDIKSI
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-amber-950 text-sm">{{ number_format($f['prediksi'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('predictionChart').getContext('2d');
                
                const historicalLabels = {!! json_encode(array_column($historical, 'tahun')) !!};
                const historicalData = {!! json_encode(array_column($historical, 'stunting')) !!};
                
                const forecastLabels = {!! json_encode(array_column($forecast, 'tahun')) !!};
                const forecastData = {!! json_encode(array_column($forecast, 'prediksi')) !!};
                
                // Pad historical data with nulls to match forecast timeline, except the last historical point
                const lastHistVal = historicalData[historicalData.length - 1];
                const fullForecastData = Array(historicalData.length - 1).fill(null);
                fullForecastData.push(lastHistVal);
                fullForecastData.push(...forecastData);
                
                const allLabels = [...historicalLabels, ...forecastLabels];
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: allLabels,
                        datasets: [
                            {
                                label: 'Data Historis (%)',
                                data: [...historicalData, ...Array(forecastData.length).fill(null)],
                                borderColor: '#0d9488', // Teal
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                fill: true,
                                tension: 0.3,
                                borderWidth: 3,
                                pointBackgroundColor: '#0d9488',
                                pointBorderColor: '#171717',
                                pointBorderWidth: 2,
                                pointRadius: 5
                            },
                            {
                                label: 'Proyeksi Regresi (%)',
                                data: fullForecastData,
                                borderColor: '#d97706', // Amber/Orange
                                borderDash: [6, 6],
                                backgroundColor: 'transparent',
                                tension: 0.1,
                                borderWidth: 3,
                                pointBackgroundColor: '#f59e0b',
                                pointBorderColor: '#171717',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { family: 'Courier New', weight: 'bold', size: 11 },
                                    color: '#171717'
                                }
                            },
                            tooltip: {
                                backgroundColor: '#171717',
                                titleFont: { family: 'Courier New', weight: 'bold' },
                                bodyFont: { family: 'Courier New' },
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: '#e5e5d8' },
                                border: { width: 2, color: '#171717' },
                                ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#e5e5d8' },
                                border: { width: 2, color: '#171717' },
                                title: {
                                    display: true,
                                    text: 'PREVALENSI (%)',
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
</div>
@endsection
