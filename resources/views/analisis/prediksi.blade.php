@extends('layouts.app')

@section('title', 'Prediksi Stunting')
@section('page-title', 'Peramalan Tren Kasus Stunting')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Hasil Prediksi Time-Series (Linear Regression)</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ ($status ?? 'error') == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ ($status ?? 'error') == 'success' ? 'Analytics Engine Aktif' : 'Error' }}
        </span>
    </div>

    <div class="p-6">
        @if(($status ?? 'error') == 'error')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Gagal Memuat Analisis</h3>
                        <p class="text-sm text-red-700 mt-1">{{ $message ?? 'Service analitik tidak berjalan atau tidak dapat dijangkau.' }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">
                    <h4 class="text-sm font-medium text-blue-800 uppercase tracking-wide mb-1">Tren Analisis</h4>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-blue-900">{{ $trend }}</span>
                        <span class="text-sm font-medium text-blue-600">Slope: {{ $slope }}</span>
                    </div>
                    <p class="mt-2 text-sm text-blue-700">Estimasi arah perkembangan prevalensi stunting secara keseluruhan berdasarkan data historis {{ count($historical) }} tahun terakhir.</p>
                </div>

                <div class="lg:col-span-2 border border-gray-200 rounded-lg p-5 bg-white">
                    <h4 class="text-sm font-medium text-gray-800 mb-4">Grafik Prediksi Prevalensi Stunting (%)</h4>
                    <div class="relative h-64 w-full">
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-lg font-bold text-gray-800 mb-3">Tabel Prediksi 3 Tahun ke Depan</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi Prevalensi (%)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($historical as $h)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $h['tahun'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Historis</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($h['stunting'], 2) }}%</td>
                            </tr>
                            @endforeach
                            @foreach($forecast as $f)
                            <tr class="bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-yellow-800">{{ $f['tahun'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 text-yellow-800">Prediksi</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-yellow-800">{{ number_format($f['prediksi'], 2) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    fill: true,
                                    tension: 0.3,
                                    borderWidth: 3
                                },
                                {
                                    label: 'Prediksi (Linear Regression)',
                                    data: fullForecastData,
                                    borderColor: 'rgb(245, 158, 11)',
                                    borderDash: [5, 5],
                                    backgroundColor: 'transparent',
                                    tension: 0.1,
                                    borderWidth: 3,
                                    pointBackgroundColor: 'rgb(245, 158, 11)',
                                    pointRadius: 5
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '%';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Prevalensi Stunting (%)'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    </div>
</div>
@endsection
