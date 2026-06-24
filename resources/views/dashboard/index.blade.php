@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Analisa Kesehatan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

@section('content')
<div class="space-y-6">
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Faskes -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover border-l-4 border-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Fasilitas Kesehatan</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalFaskes }}</h3>
                    <p class="text-xs {{ $faskesGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <span class="font-semibold">{{ $faskesGrowth >= 0 ? '+' : '' }}{{ $faskesGrowth }}%</span> dari tahun lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Total Tenaga Kesehatan -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover border-l-4 border-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tenaga Kesehatan</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalNakes, 0, ',', '.') }}</h3>
                    <p class="text-xs {{ $nakesGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <span class="font-semibold">{{ $nakesGrowth >= 0 ? '+' : '' }}{{ $nakesGrowth }}%</span> dari tahun lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Prevalensi Stunting -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Prevalensi Stunting</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($prevalensiStunting, 2) }}%</h3>
                    <p class="text-xs {{ $stuntingTrend <= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <span class="font-semibold">{{ $stuntingTrend > 0 ? '+' : '' }}{{ $stuntingTrend }}%</span> dari tahun lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- AKI -->
        <div class="bg-white rounded-lg shadow-md p-6 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Angka Kematian Ibu</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($aki, 1) }}</h3>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="font-semibold">per 100.000</span> kelahiran hidup
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kasus Penyakit Terbanyak -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">10 Kasus Penyakit Terbanyak</h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                </button>
            </div>
            <div class="h-64">
                <canvas id="penyakitChart"></canvas>
            </div>
        </div>
        
        <!-- Stunting per Kecamatan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">Prevalensi Stunting by Kecamatan (%)</h3>
                <a href="{{ route('analisis.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Lihat Detail &rarr;</a>
            </div>
            <div class="h-64">
                <canvas id="stuntingChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Map & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Peta Stunting -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Peta Sebaran Stunting</h3>
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Live</span>
            </div>
            <div class="flex-1 bg-gray-200 min-h-[300px]" id="map" style="z-index: 1;">
                <!-- Map container -->
            </div>
        </div>
        
        <!-- Quick Stats & Imunisasi -->
        <div class="space-y-6">
            <!-- Cakupan Imunisasi -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-900 mb-4">Cakupan Imunisasi Dasar</h3>
                <div class="h-40 relative">
                    <canvas id="imunisasiChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center flex-col pt-4">
                        <span class="text-2xl font-bold text-gray-900">{{ $imunisasiLengkap }}%</span>
                        <span class="text-xs text-gray-500">Lengkap</span>
                    </div>
                </div>
            </div>
            
            <!-- Highlight Alerts -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Peringatan Sistem
                </h3>
                
                @if(count($alerts) > 0)
                    <div class="space-y-3">
                        @foreach($alerts as $alert)
                        <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded text-sm">
                            <p class="font-semibold text-red-800">{{ $alert->title }}</p>
                            <p class="text-red-600 text-xs mt-1">{{ $alert->message }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm">Tidak ada peringatan aktif saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
// Format Rupiah / Numbers
const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);

// Colors configuration
const chartColors = {
    red: '#DC2626',
    blue: '#2563EB',
    yellow: '#D97706',
    green: '#10B981',
    gray: '#4B5563',
    indigo: '#4F46E5',
    purple: '#7C3AED',
    pink: '#DB2777'
};

// Penyakit Chart
const penyakitCtx = document.getElementById('penyakitChart').getContext('2d');
new Chart(penyakitCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($penyakitLabels) !!},
        datasets: [{
            label: 'Jumlah Kasus',
            data: {!! json_encode($penyakitData) !!},
            backgroundColor: chartColors.red,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Stunting Chart
const stuntingCtx = document.getElementById('stuntingChart').getContext('2d');
new Chart(stuntingCtx, {
    type: 'bar', // Using bar instead of horizontal for compact view
    data: {
        labels: {!! json_encode($kecamatanLabels) !!},
        datasets: [{
            label: 'Prevalensi Stunting (%)',
            data: {!! json_encode($stuntingData) !!},
            backgroundColor: chartColors.blue,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { 
                ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 45
                }
            },
            y: { 
                beginAtZero: true,
                max: 50
            }
        }
    }
});

// Imunisasi Chart
const imunisasiCtx = document.getElementById('imunisasiChart').getContext('2d');
new Chart(imunisasiCtx, {
    type: 'doughnut',
    data: {
        labels: ['Lengkap', 'Belum Lengkap'],
        datasets: [{
            data: [{!! $imunisasiLengkap !!}, {!! max(0, 100 - $imunisasiLengkap) !!}],
            backgroundColor: [chartColors.green, '#E5E7EB'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Leaflet Map for Stunting using GeoJSON
const stuntingMapData = {!! json_encode($stuntingMapData) !!};
const stuntingDict = {};
stuntingMapData.forEach(item => {
    stuntingDict[item.nama.toLowerCase()] = item.prevalensi || 0;
});

const map = L.map('map').setView([-7.38, 109.65], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

fetch('{{ asset("assets/peta_kecamatan.geojson") }}')
    .then(res => res.json())
    .then(data => {
        L.geoJSON(data, {
            style: function(feature) {
                let kecName = feature.properties.Kecamatan ? feature.properties.Kecamatan.toLowerCase() : '';
                let stuntingVal = stuntingDict[kecName] || 0;
                
                let color = '#10B981'; // Green (Aman)
                if(stuntingVal >= 20) color = '#EF4444'; // Red (Bahaya)
                else if(stuntingVal >= 10) color = '#F59E0B'; // Yellow (Waspada)
                
                return {
                    fillColor: color,
                    weight: 1.5,
                    opacity: 1,
                    color: '#ffffff',
                    dashArray: '3',
                    fillOpacity: 0.75
                };
            },
            onEachFeature: function(feature, layer) {
                let kecName = feature.properties.Kecamatan || 'Unknown';
                let stuntingVal = stuntingDict[kecName.toLowerCase()] || 0;
                
                let popupContent = 
                    '<div style="min-width: 150px;">' +
                        '<h4 style="margin: 0 0 5px 0; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; text-transform: capitalize;">Kecamatan ' + kecName + '</h4>' +
                        '<p style="margin: 3px 0;"><strong>Prevalensi Stunting:</strong> ' + stuntingVal.toFixed(2) + '%</p>' +
                    '</div>';
                layer.bindPopup(popupContent);
            }
        }).addTo(map);
    });
</script>
@endpush


