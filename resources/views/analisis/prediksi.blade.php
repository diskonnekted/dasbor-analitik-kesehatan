@extends('layouts.app')

@section('title', 'Prediksi Stunting')
@section('page-title', 'Peramalan Tren Kasus Stunting')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                Time-Series Forecast
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Peramalan Kasus Stunting</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Estimasi perkembangan angka stunting untuk 3 tahun ke depan berdasarkan analisis tren historis wilayah menggunakan model Linear Regression dari Python Service.
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @if(($status ?? 'error') == 'error')
        <div class="flex items-start gap-4 rounded-[28px] bg-red-50 p-6 text-red-800">
            <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5"></i>
            <div>
                <h3 class="font-medium mb-1">Gagal Memuat Analisis Prediksi</h3>
                <p class="text-sm opacity-80">{{ $message ?? 'Service analitik tidak berjalan atau tidak dapat dijangkau.' }}</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 md-card p-6 bg-md-secondary-container text-md-on-secondary-container rounded-[28px] flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-medium uppercase tracking-wider opacity-70">Arah Tren Data</h4>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-4xl font-normal capitalize">{{ $trend }}</span>
                        <span class="inline-flex items-center rounded-full bg-white/70 px-3 py-1 text-[11px] font-medium">
                            Slope: {{ $slope }}
                        </span>
                    </div>
                    <p class="mt-4 text-xs leading-relaxed opacity-80">
                        Arah slope negatif menunjukkan penurunan prevalensi stunting, sedangkan positif menunjukkan potensi peningkatan jika tidak ada intervensi tambahan.
                    </p>
                </div>
                <div class="border-t border-md-primary/20 pt-6 mt-6">
                    <span class="text-[10px] font-medium uppercase tracking-wider opacity-70 block">Metrik Input</span>
                    <span class="text-xs block mt-1">Data Input: {{ count($historical) }} Tahun Terakhir</span>
                </div>
            </div>

            <div class="lg:col-span-8 md-card p-6 md:p-8 flex flex-col">
                <h2 class="md-card-title mb-6">
                    <i data-lucide="line-chart" class="w-4 h-4 text-md-primary"></i>
                    Grafik Prediksi Tren (%)
                </h2>
                <div class="flex-grow relative min-h-[300px]">
                    <canvas id="predictionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="md-card p-6">
            <h2 class="md-card-title mb-6">
                <i data-lucide="table" class="w-4 h-4 text-md-primary"></i>
                Detail Nilai Proyeksi 3 Tahun
            </h2>
            <div class="overflow-x-auto">
                <table class="md-table">
                    <thead>
                        <tr class="border-b border-md-outline/20">
                            <th>Tahun</th>
                            <th>Status Data</th>
                            <th class="text-right">Prevalensi Stunting</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-md-outline/10">
                        @foreach($historical as $h)
                            <tr class="hover:bg-md-surface-container/60 transition-colors">
                                <td class="font-medium">{{ $h['tahun'] }}</td>
                                <td>
                                    <span class="md-badge">Historis</span>
                                </td>
                                <td class="text-right">{{ number_format($h['stunting'], 2) }}%</td>
                            </tr>
                        @endforeach
                        @foreach($forecast as $f)
                            <tr class="bg-md-secondary-container/40 hover:bg-md-secondary-container/70 transition-colors">
                                <td class="font-medium">{{ $f['tahun'] }}</td>
                                <td>
                                    <span class="inline-flex items-center rounded-full bg-md-primary px-3 py-1 text-[10px] font-medium text-white">
                                        Prediksi
                                    </span>
                                </td>
                                <td class="text-right font-medium">{{ number_format($f['prediksi'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const historicalLabels = {!! json_encode(array_column($historical, 'tahun')) !!};
                const historicalData = {!! json_encode(array_column($historical, 'stunting')) !!};
                const forecastLabels = {!! json_encode(array_column($forecast, 'tahun')) !!};
                const forecastData = {!! json_encode(array_column($forecast, 'prediksi')) !!};

                const lastHistVal = historicalData[historicalData.length - 1];
                const fullForecastData = Array(historicalData.length - 1).fill(null);
                fullForecastData.push(lastHistVal);
                fullForecastData.push(...forecastData);

                new Chart(document.getElementById('predictionChart'), {
                    type: 'line',
                    data: {
                        labels: [...historicalLabels, ...forecastLabels],
                        datasets: [
                            {
                                label: 'Data Historis (%)',
                                data: [...historicalData, ...Array(forecastData.length).fill(null)],
                                borderColor: '#6750A4',
                                backgroundColor: 'rgba(103, 80, 164, 0.12)',
                                fill: true,
                                tension: 0.3,
                                borderWidth: 2,
                                pointBackgroundColor: '#6750A4',
                                pointBorderColor: '#FFFFFF',
                                pointBorderWidth: 2,
                                pointRadius: 4
                            },
                            {
                                label: 'Proyeksi Regresi (%)',
                                data: fullForecastData,
                                borderColor: '#7D5260',
                                borderDash: [6, 6],
                                backgroundColor: 'transparent',
                                tension: 0.1,
                                borderWidth: 2,
                                pointBackgroundColor: '#7D5260',
                                pointBorderColor: '#FFFFFF',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7
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
                                    font: { family: "'Roboto', sans-serif", size: 11 },
                                    color: '#49454F',
                                    usePointStyle: true,
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                backgroundColor: '#49454F',
                                cornerRadius: 12,
                                padding: 12,
                                titleFont: { family: "'Roboto', sans-serif", weight: '500' },
                                bodyFont: { family: "'Roboto', sans-serif" },
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(121,116,126,0.2)' },
                                border: { width: 0 },
                                ticks: { font: { family: "'Roboto', sans-serif", size: 11 }, color: '#49454F' }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(121,116,126,0.2)' },
                                border: { width: 0 },
                                title: {
                                    display: true,
                                    text: 'Prevalensi (%)',
                                    font: { family: "'Roboto', sans-serif", size: 10 },
                                    color: '#49454F'
                                },
                                ticks: { font: { family: "'Roboto', sans-serif", size: 11 }, color: '#49454F' }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</div>
@endsection
