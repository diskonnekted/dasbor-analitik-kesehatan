@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Analisa Kesehatan')

@section('content')
<div class="space-y-6 md:space-y-8">

    <!-- Hero -->
    <section class="relative overflow-hidden rounded-[32px] md:rounded-[48px] bg-md-surface-container p-8 md:p-12">
        <div class="pointer-events-none absolute -top-28 -right-20 h-96 w-96 rounded-full bg-md-primary/25 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-16 h-80 w-80 rounded-full bg-md-tertiary/25 blur-3xl" aria-hidden="true"></div>

        <div class="relative max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-md-secondary-container px-4 py-1.5 text-xs font-medium text-md-on-secondary-container">
                <i data-lucide="shield-check" class="h-3.5 w-3.5" aria-hidden="true"></i>
                Pemerintah Kabupaten Banjarnegara
            </span>
            <h1 class="mt-5 text-3xl md:text-4xl font-medium leading-tight tracking-tight text-md-on-surface">
                Dasbor Analitik Kesehatan
            </h1>
            <p class="mt-3 max-w-xl text-base leading-relaxed text-md-on-surface-variant">
                Pemantauan real-time faskes, ketersediaan tenaga medis, dan prevalensi stunting secara terpadu di wilayah Kabupaten Banjarnegara.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('laporan.index') }}"
                   class="inline-flex h-11 items-center gap-2 rounded-full bg-md-primary px-6 text-sm font-medium text-md-on-primary shadow-sm transition-all duration-300 ease-md hover:bg-md-primary/90 hover:shadow-md active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                    <i data-lucide="file-text" class="h-4 w-4" aria-hidden="true"></i>
                    Lihat Laporan
                </a>
                <a href="{{ route('analisis.index') }}"
                   class="inline-flex h-11 items-center rounded-full border border-md-outline/40 px-6 text-sm font-medium text-md-primary transition-all duration-300 ease-md hover:bg-md-primary/5 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary focus-visible:ring-offset-2">
                    Analisa Lanjutan
                </a>
            </div>
        </div>
    </section>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Fasilitas Kesehatan', 'value' => $totalFaskes, 'icon' => 'building-2', 'sub' => ($faskesGrowth >= 0 ? '+' : '') . $faskesGrowth . '% tahunan', 'accent' => 'bg-md-primary', 'tone' => 'text-md-primary'],
            ['label' => 'Tenaga Medis', 'value' => number_format($totalNakes, 0, ',', '.'), 'icon' => 'users', 'sub' => ($nakesGrowth >= 0 ? '+' : '') . $nakesGrowth . '% tahunan', 'accent' => 'bg-md-tertiary', 'tone' => 'text-md-tertiary'],
            ['label' => 'Prevalensi Stunting', 'value' => number_format($prevalensiStunting, 2) . '%', 'icon' => 'trending-up', 'sub' => ($stuntingTrend > 0 ? '+' : '') . $stuntingTrend . '% tahunan', 'accent' => 'bg-md-primary', 'tone' => 'text-md-primary'],
            ['label' => 'Kematian Ibu (AKI)', 'value' => number_format($aki, 1), 'icon' => 'heart', 'sub' => 'Total kasus tercatat', 'accent' => 'bg-md-tertiary', 'tone' => 'text-md-tertiary'],
        ] as $kpi)
            <div class="group flex flex-col justify-between rounded-3xl bg-md-surface-container p-6 shadow-sm transition-all duration-300 ease-md hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-md-on-surface-variant">{{ $kpi['label'] }}</p>
                        <h3 class="mt-2 text-3xl font-medium tracking-tight text-md-on-surface">{{ $kpi['value'] }}</h3>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $kpi['accent'] }}/15 {{ $kpi['tone'] }} transition-transform duration-300 ease-md group-hover:scale-110">
                        <i data-lucide="{{ $kpi['icon'] }}" class="h-5 w-5" aria-hidden="true"></i>
                    </div>
                </div>
                <span class="mt-5 inline-flex w-fit rounded-full bg-md-primary/5 px-3 py-1 text-xs font-medium {{ $kpi['tone'] }}">{{ $kpi['sub'] }}</span>
            </div>
        @endforeach
    </div>

    <!-- Main Charts -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-[28px] bg-md-surface-container p-6 shadow-sm transition-shadow duration-300 ease-md hover:shadow-md">
            <h3 class="mb-6 flex items-center gap-2 text-base font-medium text-md-on-surface">
                <i data-lucide="activity" class="h-4.5 w-4.5 text-md-primary" aria-hidden="true"></i>
                Akumulasi 10 Penyakit Terbanyak
            </h3>
            <div class="h-64">
                <canvas id="penyakitChart" role="img" aria-label="Grafik akumulasi 10 penyakit terbanyak"></canvas>
            </div>
        </div>

        <div class="rounded-[28px] bg-md-surface-container p-6 shadow-sm transition-shadow duration-300 ease-md hover:shadow-md">
            <div class="mb-6 flex items-center justify-between gap-3">
                <h3 class="flex items-center gap-2 text-base font-medium text-md-on-surface">
                    <i data-lucide="bar-chart-2" class="h-4.5 w-4.5 text-md-primary" aria-hidden="true"></i>
                    Prevalensi Stunting per Kecamatan (%)
                </h3>
                <a href="{{ route('analisis.index') }}" class="shrink-0 rounded-full px-3 py-1.5 text-sm font-medium text-md-primary transition-colors duration-200 hover:bg-md-primary/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-md-primary">
                    Detail &rarr;
                </a>
            </div>
            <div class="h-64">
                <canvas id="stuntingChart" role="img" aria-label="Grafik prevalensi stunting per kecamatan"></canvas>
            </div>
        </div>
    </div>

    <!-- Map & side widgets -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Peta Stunting -->
        <div class="overflow-hidden rounded-[28px] bg-md-surface-container shadow-sm transition-shadow duration-300 ease-md hover:shadow-md lg:col-span-2">
            <div class="flex items-center justify-between gap-3 p-6">
                <h3 class="flex items-center gap-2 text-base font-medium text-md-on-surface">
                    <i data-lucide="map" class="h-4.5 w-4.5 text-md-primary" aria-hidden="true"></i>
                    Peta Sebaran Stunting
                </h3>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-md-secondary-container px-3 py-1 text-xs font-medium text-md-on-secondary-container">
                    <i data-lucide="map-pin" class="h-3 w-3" aria-hidden="true"></i>
                    Live WebGIS
                </span>
            </div>
            <div class="relative">
                <div id="map" class="h-[340px] w-full" style="z-index: 1;"></div>
                <!-- Legenda -->
                <div class="pointer-events-none absolute right-4 top-4 z-[500] flex flex-col gap-1.5 rounded-2xl bg-md-surface/90 p-3 shadow-md backdrop-blur-sm">
                    @foreach ([['#ef4444', 'Bahaya (≥20%)'], ['#f59e0b', 'Waspada (10–20%)'], ['#10b981', 'Aman (<10%)']] as [$c, $l])
                        <span class="flex items-center gap-2 text-xs text-md-on-surface">
                            <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $c }}"></span>{{ $l }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Widget samping -->
        <div class="space-y-6">
            <!-- Rasio Nakes -->
            <div class="rounded-[28px] bg-md-surface-container p-6 shadow-sm transition-shadow duration-300 ease-md hover:shadow-md">
                <h3 class="mb-6 flex items-center gap-2 text-base font-medium text-md-on-surface">
                    <i data-lucide="users" class="h-4.5 w-4.5 text-md-primary" aria-hidden="true"></i>
                    Rasio Ketersediaan Nakes
                </h3>
                <div class="space-y-6 text-sm">
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-md-on-surface-variant">Dokter Umum</span>
                            <span class="rounded-full bg-md-primary/10 px-3 py-0.5 text-xs font-medium text-md-primary">1 : {{ number_format($rasioDokter, 0, ',', '.') }}</span>
                        </div>
                        @php $percentDokter = min(100, (2500 / max(1, $rasioDokter)) * 100); @endphp
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-md-surface-container-low">
                            <div class="h-full rounded-full bg-md-primary" style="width: {{ $percentDokter }}%"></div>
                        </div>
                        <p class="mt-1.5 text-xs text-md-on-surface-variant">Standar ideal WHO: 1 dokter per 2.500 penduduk</p>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-md-on-surface-variant">Bidan Desa</span>
                            <span class="rounded-full bg-md-tertiary/10 px-3 py-0.5 text-xs font-medium text-md-tertiary">1 : {{ number_format($rasioBidan, 0, ',', '.') }}</span>
                        </div>
                        @php $percentBidan = min(100, (1000 / max(1, $rasioBidan)) * 100); @endphp
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-md-surface-container-low">
                            <div class="h-full rounded-full bg-md-tertiary" style="width: {{ $percentBidan }}%"></div>
                        </div>
                        <p class="mt-1.5 text-xs text-md-on-surface-variant">Standar ideal: 1 bidan per 1.000 penduduk</p>
                    </div>
                </div>
            </div>

            <!-- Cakupan Program -->
            <div class="rounded-[28px] bg-md-surface-container p-6 shadow-sm transition-shadow duration-300 ease-md hover:shadow-md">
                <h3 class="mb-4 flex items-center gap-2 text-base font-medium text-md-on-surface">
                    <i data-lucide="shield-check" class="h-4.5 w-4.5 text-md-primary" aria-hidden="true"></i>
                    Cakupan Program Prioritas
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('imunisasi.index') }}" class="group flex items-center justify-between rounded-3xl bg-md-secondary-container p-4 transition-all duration-300 ease-md hover:bg-md-secondary-container/70 active:scale-[0.98]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-md-primary/15 text-md-primary">
                                <i data-lucide="credit-card" class="h-4.5 w-4.5" aria-hidden="true"></i>
                            </div>
                            <div class="leading-tight">
                                <p class="text-sm font-medium text-md-on-secondary-container">Kepesertaan JKN/BPJS</p>
                                <p class="text-xs text-md-on-secondary-container/60">Universal Health Coverage</p>
                            </div>
                        </div>
                        <span class="text-lg font-medium text-md-on-secondary-container">{{ $cakupanBPJS }}%</span>
                    </a>
                    <div class="flex items-center justify-between rounded-3xl bg-md-tertiary/10 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-md-tertiary/15 text-md-tertiary">
                                <i data-lucide="sprout" class="h-4.5 w-4.5" aria-hidden="true"></i>
                            </div>
                            <div class="leading-tight">
                                <p class="text-sm font-medium text-md-on-surface">Desa ODF</p>
                                <p class="text-xs text-md-on-surface-variant">Open Defecation Free</p>
                            </div>
                        </div>
                        <span class="text-lg font-medium text-md-on-surface">{{ $desaODF }} / {{ $totalDesa }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 4px 12px rgba(28, 27, 31, 0.15); }
    .leaflet-popup-content { font-family: 'Roboto', sans-serif; margin: 12px 16px; }
    .leaflet-container { font-family: 'Roboto', sans-serif; background: #E7E0EC; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mdFont = { family: "'Roboto', sans-serif" };
    const mdTick = { font: { ...mdFont, size: 11 }, color: '#49454F' };
    const mdGrid = { color: 'rgba(121, 116, 126, 0.2)' };

    // Penyakit Chart
    new Chart(document.getElementById('penyakitChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($penyakitLabels) !!},
            datasets: [{
                label: 'Jumlah Kasus',
                data: {!! json_encode($penyakitData) !!},
                backgroundColor: '#6750A4',
                hoverBackgroundColor: '#7F67BE',
                borderRadius: 8,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { width: 0 }, ticks: mdTick },
                y: { beginAtZero: true, grid: mdGrid, border: { width: 0 }, ticks: mdTick }
            }
        }
    });

    // Stunting Chart
    new Chart(document.getElementById('stuntingChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($kecamatanLabels) !!},
            datasets: [{
                label: 'Prevalensi Stunting (%)',
                data: {!! json_encode($stuntingData) !!},
                backgroundColor: '#7D5260',
                hoverBackgroundColor: '#96626F',
                borderRadius: 8,
                maxBarThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { width: 0 }, ticks: { ...mdTick, size: 9, autoSkip: false, maxRotation: 45, minRotation: 45 } },
                y: { beginAtZero: true, max: 50, grid: mdGrid, border: { width: 0 }, ticks: mdTick }
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
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri'
    }).addTo(map);

    fetch('{{ asset("assets/peta_kecamatan.geojson") }}')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: function(feature) {
                    let kecName = feature.properties.Kecamatan ? feature.properties.Kecamatan.toLowerCase() : '';
                    let stuntingVal = stuntingDict[kecName] || 0;

                    let color = '#10b981'; // Aman
                    if (stuntingVal >= 20) color = '#ef4444'; // Bahaya
                    else if (stuntingVal >= 10) color = '#f59e0b'; // Waspada

                    return { fillColor: color, weight: 1.5, opacity: 1, color: '#49454F', fillOpacity: 0.75 };
                },
                onEachFeature: function(feature, layer) {
                    let kecName = feature.properties.Kecamatan || 'Unknown';
                    let stuntingVal = stuntingDict[kecName.toLowerCase()] || 0;

                    layer.on({
                        mouseover: function(e) {
                            e.target.setStyle({ weight: 3, fillOpacity: 0.9 });
                            e.target.bringToFront();
                        },
                        mouseout: function(e) {
                            e.target.setStyle({ weight: 1.5, fillOpacity: 0.75 });
                        }
                    });

                    layer.bindPopup(
                        '<div style="min-width: 160px; color: #1C1B1F;">' +
                            '<h4 style="margin: 0 0 6px; font-size: 13px; font-weight: 500; color: #6750A4;">Kec. ' + kecName + '</h4>' +
                            '<p style="margin: 0; font-size: 12px;">Stunting: <strong>' + stuntingVal.toFixed(2) + '%</strong></p>' +
                        '</div>'
                    );
                }
            }).addTo(map);
        });
});
</script>
@endpush
