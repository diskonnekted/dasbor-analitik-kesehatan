@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Analisa Kesehatan')

@section('push-styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endsection

@section('content')
<div class="space-y-8">
    <!-- Top Hero Banner (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717]">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-600 text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                Pemerintah Kabupaten Banjarnegara
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Dasbor Analitik Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Pemantauan real-time faskes, ketersediaan tenaga medis, dan prevalensi stunting secara terpadu di wilayah Kabupaten Banjarnegara.
            </p>
        </div>
    </div>

    <!-- KPI Cards (Neo-Brutalist) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Faskes -->
        <div class="bg-red-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all flex flex-col justify-between">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-mono font-black text-red-900 uppercase tracking-wider">Fasilitas Kesehatan</p>
                    <h3 class="text-3xl font-mono font-black text-[#171717] mt-3">{{ $totalFaskes }}</h3>
                </div>
                <div class="w-10 h-10 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="building-2" class="w-5 h-5 text-red-600"></i>
                </div>
            </div>
            <div class="mt-5 pt-3 border-t border-red-300">
                <span class="inline-block px-2 py-0.5 text-[9px] font-mono font-black border border-[#171717] bg-white text-red-950">
                    {{ $faskesGrowth >= 0 ? '+' : '' }}{{ $faskesGrowth }}% TAHUNAN
                </span>
            </div>
        </div>
        
        <!-- Total Tenaga Kesehatan -->
        <div class="bg-blue-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all flex flex-col justify-between">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-mono font-black text-blue-900 uppercase tracking-wider">Tenaga Medis</p>
                    <h3 class="text-3xl font-mono font-black text-[#171717] mt-3">{{ number_format($totalNakes, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-5 pt-3 border-t border-blue-300">
                <span class="inline-block px-2 py-0.5 text-[9px] font-mono font-black border border-[#171717] bg-white text-blue-950">
                    {{ $nakesGrowth >= 0 ? '+' : '' }}{{ $nakesGrowth }}% TAHUNAN
                </span>
            </div>
        </div>
        
        <!-- Prevalensi Stunting -->
        <div class="bg-amber-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all flex flex-col justify-between">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-mono font-black text-amber-900 uppercase tracking-wider">Prevalensi Stunting</p>
                    <h3 class="text-3xl font-mono font-black text-[#171717] mt-3">{{ number_format($prevalensiStunting, 2) }}%</h3>
                </div>
                <div class="w-10 h-10 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="trending-up" class="w-5 h-5 text-amber-600"></i>
                </div>
            </div>
            <div class="mt-5 pt-3 border-t border-amber-300">
                <span class="inline-block px-2 py-0.5 text-[9px] font-mono font-black border border-[#171717] bg-white text-amber-950">
                    {{ $stuntingTrend > 0 ? '+' : '' }}{{ $stuntingTrend }}% TAHUNAN
                </span>
            </div>
        </div>
        
        <!-- AKI -->
        <div class="bg-rose-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] hover:shadow-[6px_6px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all flex flex-col justify-between">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-mono font-black text-rose-900 uppercase tracking-wider">Kematian Ibu (AKI)</p>
                    <h3 class="text-3xl font-mono font-black text-[#171717] mt-3">{{ number_format($aki, 1) }}</h3>
                </div>
                <div class="w-10 h-10 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center flex-shrink-0">
                    <i data-lucide="heart" class="w-5 h-5 text-rose-600"></i>
                </div>
            </div>
            <div class="mt-5 pt-3 border-t border-rose-300">
                <span class="inline-block px-2 py-0.5 text-[9px] font-mono font-black border border-[#171717] bg-white text-rose-950">
                    TOTAL KASUS TERCATAT
                </span>
            </div>
        </div>
    </div>

    <!-- Main Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Kasus Penyakit Terbanyak -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="activity" class="w-4 h-4 text-red-600"></i>
                Akumulasi 10 Penyakit Terbanyak
            </h3>
            <div class="h-64 border-2 border-[#171717] p-2 bg-[#f8f8f6]">
                <canvas id="penyakitChart"></canvas>
            </div>
        </div>
        
        <!-- Stunting per Kecamatan -->
        <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b-2 border-[#171717] pb-2">
                <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-blue-600"></i>
                    Prevalensi Stunting per Kecamatan (%)
                </h3>
                <a href="{{ route('analisis.index') }}" class="text-[10px] font-mono font-black uppercase tracking-wider text-red-600 hover:underline">
                    Analisa Detail &rarr;
                </a>
            </div>
            <div class="h-64 border-2 border-[#171717] p-2 bg-[#f8f8f6]">
                <canvas id="stuntingChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Map & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Peta Stunting -->
        <div class="lg:col-span-2 bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] flex flex-col">
            <div class="p-6 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
                <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] flex items-center gap-2">
                    <i data-lucide="map" class="w-4.5 h-4.5 text-emerald-600"></i>
                    Peta Sebaran Stunting
                </h3>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border-2 border-[#171717] shadow-[1px_1px_0px_0px_#171717] text-[10px] font-mono font-bold tracking-wider text-emerald-700 uppercase">
                    Live WebGIS
                </span>
            </div>
            <div class="flex-1 min-h-[300px] border-t-2 border-[#171717]" id="map" style="z-index: 1;">
                <!-- Map container -->
            </div>
        </div>
        
        <!-- Nakes & Lingkungan -->
        <div class="space-y-8">
            <!-- Rasio Nakes -->
            <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
                <h3 class="text-xs font-serif font-black uppercase tracking-wider text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="users" class="w-4.5 h-4.5 text-indigo-600"></i>
                    Rasio Ketersediaan Nakes
                </h3>
                
                <div class="space-y-6 font-mono font-bold text-xs">
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-neutral-700 uppercase">Dokter Umum</span>
                            <span class="text-indigo-800 bg-indigo-50 border border-indigo-400 px-1.5 py-0.5">1 : {{ number_format($rasioDokter, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-neutral-100 border-2 border-[#171717] h-4 flex overflow-hidden">
                            @php
                                $percentDokter = min(100, (2500 / max(1, $rasioDokter)) * 100);
                            @endphp
                            <div class="bg-indigo-500 border-r border-[#171717]" style="width: {{ $percentDokter }}%"></div>
                        </div>
                        <p class="text-[9px] text-neutral-500 mt-1">Standar ideal WHO: 1 Dokter per 2.500 Penduduk</p>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-neutral-700 uppercase">Bidan Desa</span>
                            <span class="text-rose-800 bg-rose-50 border border-rose-400 px-1.5 py-0.5">1 : {{ number_format($rasioBidan, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-neutral-100 border-2 border-[#171717] h-4 flex overflow-hidden">
                            @php
                                $percentBidan = min(100, (1000 / max(1, $rasioBidan)) * 100);
                            @endphp
                            <div class="bg-rose-400 border-r border-[#171717]" style="width: {{ $percentBidan }}%"></div>
                        </div>
                        <p class="text-[9px] text-neutral-500 mt-1">Standar ideal: 1 Bidan per 1.000 Penduduk</p>
                    </div>
                </div>
            </div>
            
            <!-- Lingkungan & BPJS -->
            <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
                <h3 class="text-xs font-serif font-black uppercase tracking-wider text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                    <i data-lucide="shield-check" class="w-4.5 h-4.5 text-teal-600"></i>
                    Cakupan Program Prioritas
                </h3>
                
                <div class="space-y-4 font-mono font-bold text-xs">
                    <div class="flex items-center justify-between p-3 border-2 border-[#171717] bg-teal-50 shadow-[2px_2px_0px_0px_#171717]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-teal-700"></i>
                            <div class="leading-none">
                                <p class="text-[10px] text-teal-900 uppercase">Kepesertaan JKN/BPJS</p>
                                <p class="text-[8px] text-neutral-500 mt-0.5">Universal Health Coverage</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-teal-950">{{ $cakupanBPJS }}%</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 border-2 border-[#171717] bg-emerald-50 shadow-[2px_2px_0px_0px_#171717]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="sprout" class="w-4 h-4 text-emerald-700"></i>
                            <div class="leading-none">
                                <p class="text-[10px] text-emerald-900 uppercase">Desa ODF</p>
                                <p class="text-[8px] text-neutral-500 mt-0.5">Open Defecation Free</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-emerald-950">{{ $desaODF }} / {{ $totalDesa }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format Rupiah / Numbers
    const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);

    const chartColors = {
        red: '#ef4444',
        blue: '#3b82f6',
        dark: '#171717'
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
                borderColor: chartColors.dark,
                borderWidth: 2,
                borderRadius: 0
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
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                },
                y: { 
                    beginAtZero: true,
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                }
            }
        }
    });

    // Stunting Chart
    const stuntingCtx = document.getElementById('stuntingChart').getContext('2d');
    new Chart(stuntingCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kecamatanLabels) !!},
            datasets: [{
                label: 'Prevalensi Stunting (%)',
                data: {!! json_encode($stuntingData) !!},
                backgroundColor: chartColors.blue,
                borderColor: chartColors.dark,
                borderWidth: 2,
                borderRadius: 0
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
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: {
                        font: { family: 'Courier New', weight: 'bold', size: 9 },
                        color: '#171717',
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: { 
                    beginAtZero: true,
                    max: 50,
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                }
            }
        }
    });

    // Leaflet Map for Stunting using GeoJSON
    const stuntingMapData = {!! json_encode($stuntingMapData) !!};
    const stuntingDict = {};
    stuntingMapData.forEach(item => {
        stuntingDict[item.nama.toLowerCase()] = item.prevalensi || 0;
    });

    const map = L.map('map').setView([-7.38, 109.65], 11);
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
                    
                    let color = '#10b981'; // Green (Aman)
                    if(stuntingVal >= 20) color = '#ef4444'; // Red (Bahaya)
                    else if(stuntingVal >= 10) color = '#f59e0b'; // Yellow (Waspada)
                    
                    return {
                        fillColor: color,
                        weight: 2,
                        opacity: 1,
                        color: '#171717',
                        dashArray: '3',
                        fillOpacity: 0.8
                    };
                },
                onEachFeature: function(feature, layer) {
                    let kecName = feature.properties.Kecamatan || 'Unknown';
                    let stuntingVal = stuntingDict[kecName.toLowerCase()] || 0;
                    
                    layer.on({
                        mouseover: function(e) {
                            var l = e.target;
                            l.setStyle({ weight: 3, color: '#171717', dashArray: '', fillOpacity: 0.9 });
                            l.bringToFront();
                        },
                        mouseout: function(e) {
                            l = e.target;
                            l.setStyle({ weight: 2, color: '#171717', dashArray: '3', fillOpacity: 0.8 });
                        }
                    });

                    let popupContent = 
                        '<div style="font-family: \'Courier New\', monospace; font-size: 11px; font-weight: bold; color: #171717; min-width: 160px; padding: 4px;">' +
                            '<h4 style="margin: 0 0 6px 0; font-size: 12px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid #171717; padding-bottom: 3px;">Kec. ' + kecName + '</h4>' +
                            '<p style="margin: 3px 0;">Stunting: ' + stuntingVal.toFixed(2) + '%</p>' +
                        '</div>';
                    layer.bindPopup(popupContent);
                }
            }).addTo(map);
        });
});
</script>
@endpush
