@extends('layouts.app')

@section('title', 'Peta Sarana Kesehatan')
@section('page-title', 'Kepadatan Apotek & Sarana Kesehatan')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[32px] bg-md-surface-container p-8">
        <div class="pointer-events-none absolute -top-20 -left-16 h-64 w-64 rounded-full bg-md-tertiary/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative max-w-3xl">
            <span class="md-badge">
                <i data-lucide="hospital" class="h-3.5 w-3.5" aria-hidden="true"></i>
                Facilities &amp; Pharmacies Density
            </span>
            <h1 class="mt-4 text-3xl font-medium tracking-tight text-md-on-surface">Sebaran Sarana Kesehatan</h1>
            <p class="mt-2 text-sm leading-relaxed text-md-on-surface-variant">
                Pemetaan spasial penyebaran Apotek, Toko Obat, Laboratorium, dan Puskesmas Pembantu/Keliling di wilayah Kabupaten Banjarnegara.
            </p>
        </div>
    </div>

    <!-- Peta -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">
                <i data-lucide="map" class="h-4.5 w-4.5 text-md-primary"></i>
                Peta Spasial (Choropleth) Kepadatan Apotek
            </h3>
            <span class="md-badge"><i data-lucide="map-pin" class="h-3 w-3" aria-hidden="true"></i> Apotek Density</span>
        </div>

        <div id="map" class="h-[500px] w-full" style="z-index: 0;"></div>

        <!-- Legenda -->
        <div class="flex flex-wrap items-center gap-3 border-t border-md-outline/10 px-6 py-4 text-xs text-md-on-surface-variant">
            <span class="font-medium uppercase tracking-wide">Legenda Kepadatan:</span>
            @foreach ([['#E7E0EC', '0'], ['#B69DF8', '1-3'], ['#8F76C6', '4-6'], ['#6750A4', '7-9'], ['#4A3773', '10+']] as [$c, $l])
                <span class="flex items-center gap-1.5 rounded-full bg-md-surface-container-low px-2.5 py-1">
                    <span class="h-3 w-3 rounded-full border border-md-outline/20" style="background: {{ $c }}"></span>
                    {{ $l }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- Grafik Per Kecamatan -->
    <div class="md-card p-6 md:p-8">
        <div class="flex items-center justify-between gap-3 mb-6">
            <h3 class="md-card-title">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-md-primary"></i>
                Jumlah Sarana Kesehatan Per Kecamatan
            </h3>
            <span class="md-badge"><i data-lucide="chart-column" class="h-3 w-3" aria-hidden="true"></i> Tahun Terkini</span>
        </div>
        <div class="h-[380px]">
            <canvas id="saranaChart" role="img" aria-label="Grafik batang jumlah sarana kesehatan per kecamatan"></canvas>
        </div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Detail Sarana Kesehatan</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Open Data BARA</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        <th>Kecamatan</th>
                        <th class="text-center bg-md-secondary-container/50">Apotek</th>
                        <th class="text-center">Toko Obat</th>
                        <th class="text-center">Pustu</th>
                        <th class="text-center">Pusling</th>
                        <th class="text-center">Laborat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b border-md-outline/10 transition-colors duration-200 hover:bg-md-primary/5">
                            <td class="font-medium">{{ $item->kecamatan->nama ?? '-' }}</td>
                            <td class="bg-md-secondary-container/50 text-center font-medium">{{ $item->apotek }}</td>
                            <td class="text-center">{{ $item->toko_obat }}</td>
                            <td class="text-center">{{ $item->puskesmas_pembantu }}</td>
                            <td class="text-center">{{ $item->puskesmas_keliling }}</td>
                            <td class="text-center">{{ $item->laborat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-md-on-surface-variant">
                                Belum ada data. Lakukan sinkronisasi terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
            <div class="border-t border-md-outline/10 px-6 py-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapData = @json($mapData);

        const map = L.map('map', { zoomSnap: 0.1 }).setView([-7.38, 109.65], 11);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        }).addTo(map);

        // Choropleth: ramp tonal ungu MD3, makin gelap makin padat
        function getColor(d) {
            return d >= 10 ? '#4A3773' :
                   d >= 7  ? '#6750A4' :
                   d >= 4  ? '#8F76C6' :
                   d >= 1  ? '#B69DF8' :
                             '#E7E0EC';
        }

        var dataKepadatan = {!! json_encode($mapData) !!};

        function normalizeName(name) {
            if (!name) return '';
            return name.toLowerCase()
                       .replace(/[\s\-]/g, '')
                       .replace('purworejo', 'purwareja');
        }

        var dict = {};
        dataKepadatan.forEach(function(item) {
            if(item.nama) {
                dict[normalizeName(item.nama)] = item;
            }
        });

        fetch('{{ asset("assets/peta_kecamatan.geojson") }}')
            .then(res => res.json())
            .then(data => {
                var geojson = L.geoJSON(data, {
                    style: function(feature) {
                        let kecName = feature.properties.Kecamatan || '';
                        let normName = normalizeName(kecName);
                        let apotekCount = dict[normName] ? dict[normName].apotek : 0;

                        return {
                            fillColor: getColor(apotekCount),
                            weight: 1.5,
                            opacity: 1,
                            color: '#49454F',
                            fillOpacity: 0.85
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        let kecName = feature.properties.Kecamatan || 'Unknown';
                        let normName = normalizeName(kecName);
                        let dataKec = dict[normName] || {
                            apotek: 0, toko_obat: 0, puskesmas_pembantu: 0, puskesmas_keliling: 0, laborat: 0
                        };

                        layer.on({
                            mouseover: function(e) {
                                var l = e.target;
                                l.setStyle({
                                    weight: 3,
                                    color: '#6750A4',
                                    fillOpacity: 0.95
                                });
                                l.bringToFront();
                            },
                            mouseout: function(e) {
                                geojson.resetStyle(e.target);
                            }
                        });

                        let popupContent = `
                            <div style="font-family: 'Roboto', sans-serif; font-size: 12px; color: #1C1B1F; min-width: 190px; padding: 2px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 500; color: #6750A4; border-bottom: 1px solid #E7E0EC; padding-bottom: 6px;">Kec. ${kecName}</h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-bottom: 8px;">
                                    <div style="background: #E8DEF8; border-radius: 8px; padding: 6px; text-align: center;">
                                        <span style="display:block; color:#49454F; font-size: 9px; letter-spacing: .04em;">APOTEK</span>
                                        <span style="font-size: 16px; font-weight: 500;">${dataKec.apotek}</span>
                                    </div>
                                    <div style="background: #E7E0EC; border-radius: 8px; padding: 6px; text-align: center;">
                                        <span style="display:block; color:#49454F; font-size: 9px; letter-spacing: .04em;">TOKO OBAT</span>
                                        <span style="font-size: 16px; font-weight: 500;">${dataKec.toko_obat}</span>
                                    </div>
                                </div>
                                <ul style="list-style:none; padding:0; margin:0; font-size: 11px; line-height: 18px; color: #49454F;">
                                    <li>Puskesmas Pembantu: <strong>${dataKec.puskesmas_pembantu}</strong></li>
                                    <li>Puskesmas Keliling: <strong>${dataKec.puskesmas_keliling}</strong></li>
                                    <li>Laboratorium: <strong>${dataKec.laborat}</strong></li>
                                </ul>
                            </div>
                        `;
                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);

                // Isi frame penuh sesuai batas polygon
                map.fitBounds(geojson.getBounds());
            });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const saranaMap = {!! json_encode($mapData->values()) !!};

        new Chart(document.getElementById('saranaChart'), {
            type: 'bar',
            data: {
                labels: saranaMap.map(d => d.nama),
                datasets: [
                    { label: 'Apotek', data: saranaMap.map(d => d.apotek), backgroundColor: '#4A3773' },
                    { label: 'Toko Obat', data: saranaMap.map(d => d.toko_obat), backgroundColor: '#6750A4' },
                    { label: 'Pustu', data: saranaMap.map(d => d.puskesmas_pembantu), backgroundColor: '#8F76C6' },
                    { label: 'Pusling', data: saranaMap.map(d => d.puskesmas_keliling), backgroundColor: '#B69DF8' },
                    { label: 'Laborat', data: saranaMap.map(d => d.laborat), backgroundColor: '#7D5260' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 10 } }
                },
                scales: {
                    x: { stacked: true, grid: { display: false } },
                    y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(121,116,126,0.2)' }, border: { width: 0 } }
                }
            }
        });
    });
</script>
@endpush
