@extends('layouts.app')

@section('title', 'Peta Sarana Kesehatan')
@section('page-title', 'Kepadatan Apotek & Sarana Kesehatan')

@section('content')
<div class="space-y-8">
    <!-- Top Hero Banner (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="hospital" class="w-3.5 h-3.5 text-cyan-400"></i>
                Facilities & Pharmacies Density
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Sebaran Sarana Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Pemetaan spasial penyebaran Apotek, Toko Obat, Laboratorium, dan Puskesmas Pembantu/Keliling di wilayah Kabupaten Banjarnegara.
            </p>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
        <div class="px-6 py-4 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] flex items-center gap-2">
                <i data-lucide="map" class="w-4.5 h-4.5 text-cyan-600"></i>
                Peta Spasial (Choropleth) Kepadatan Apotek
            </h3>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border-2 border-[#171717] shadow-[1px_1px_0px_0px_#171717] text-[10px] font-mono font-bold tracking-wider text-cyan-700 uppercase">
                Apotek Density
            </span>
        </div>
        
        <div id="map" style="height: 500px; width: 100%; z-index: 0;"></div>
        
        <!-- Legend (Neo-Brutalist) -->
        <div class="px-6 py-4 bg-white border-t-2 border-[#171717] text-xs font-mono font-bold flex flex-wrap items-center gap-4 text-[#171717]">
            <span class="font-black uppercase">Legenda Kepadatan:</span>
            <div class="flex items-center gap-1.5 px-2 py-0.5 border-2 border-[#171717] bg-[#fee2e2]">
                <span>0</span>
            </div>
            <div class="flex items-center gap-1.5 px-2 py-0.5 border-2 border-[#171717] bg-[#fca5a5]">
                <span>1-3</span>
            </div>
            <div class="flex items-center gap-1.5 px-2 py-0.5 border-2 border-[#171717] bg-[#ef4444]">
                <span>4-6</span>
            </div>
            <div class="flex items-center gap-1.5 px-2 py-0.5 border-2 border-[#171717] bg-[#b91c1c] text-white">
                <span>7-9</span>
            </div>
            <div class="flex items-center gap-1.5 px-2 py-0.5 border-2 border-[#171717] bg-[#7f1d1d] text-white">
                <span>10+</span>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
        <div class="px-6 py-5 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717]">Daftar Detail Sarana Kesehatan</h3>
            <span class="inline-block px-3 py-1 bg-cyan-200 border-2 border-[#171717] text-cyan-950 font-mono font-black text-[10px] shadow-[1.5px_1.5px_0px_0px_#171717]">
                OPEN DATA BARA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-b-2 border-[#171717] text-left text-xs font-mono font-bold">
                <thead>
                    <tr class="bg-neutral-50 border-b-2 border-[#171717]">
                        <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center bg-red-50 text-red-950">Apotek</th>
                        <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">Toko Obat</th>
                        <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">Pustu</th>
                        <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">Pusling</th>
                        <th class="px-6 py-4 uppercase tracking-wider text-center">Laborat</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#171717]">
                    @forelse ($data as $item)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 border-r-2 border-[#171717] uppercase font-serif font-black text-sm">{{ $item->kecamatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center font-black text-sm bg-red-50 text-red-700">{{ $item->apotek }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->toko_obat }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->puskesmas_pembantu }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->puskesmas_keliling }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->laborat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                                Belum ada data. Lakukan sinkronisasi terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($data->hasPages())
            <div class="px-6 py-4 bg-[#f4f4f0] border-t-2 border-[#171717] font-mono">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapData = @json($mapData);

        // Initialize map
        const map = L.map('map').setView([-7.38, 109.65], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fungsi untuk menentukan warna (Choropleth) berdasarkan jumlah apotek
        function getColor(d) {
            return d >= 10 ? '#7f1d1d' : // Paling Gelap
                   d >= 7  ? '#b91c1c' :
                   d >= 4  ? '#ef4444' :
                   d >= 1  ? '#fca5a5' :
                             '#fee2e2';  // Paling Terang (0)
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

        // Fetch GeoJSON Boundaries
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
                            weight: 2,
                            opacity: 1,
                            color: '#171717',
                            dashArray: '3',
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
                                    weight: 4,
                                    color: '#171717',
                                    dashArray: '',
                                    fillOpacity: 0.95
                                });
                                l.bringToFront();
                            },
                            mouseout: function(e) {
                                geojson.resetStyle(e.target);
                            }
                        });
                        
                        let popupContent = `
                            <div style="font-family: 'Courier New', monospace; font-size: 11px; font-weight: bold; color: #171717; min-width: 180px; padding: 4px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 900; text-transform: uppercase; border-bottom: 2px solid #171717; padding-bottom: 4px;">Kec. ${kecName}</h4>
                                <div style="display: grid; grid-template-cols: 1fr 1fr; gap: 6px; margin-bottom: 8px;">
                                    <div style="background: #fee2e2; padding: 4px; border: 1px solid #171717; text-align: center;">
                                        <span style="font-size: 8px; display:block;">APOTEK</span>
                                        <span style="font-size: 16px; font-weight:900;">${dataKec.apotek}</span>
                                    </div>
                                    <div style="background: #f4f4f0; padding: 4px; border: 1px solid #171717; text-align: center;">
                                        <span style="font-size: 8px; display:block;">TOKO OBAT</span>
                                        <span style="font-size: 16px; font-weight:900;">${dataKec.toko_obat}</span>
                                    </div>
                                </div>
                                <ul style="list-style:none; padding:0; margin:0; font-size: 9px; line-height: 14px;">
                                    <li>Puskesmas Pembantu: ${dataKec.puskesmas_pembantu}</li>
                                    <li>Puskesmas Keliling: ${dataKec.puskesmas_keliling}</li>
                                    <li>Laboratorium      : ${dataKec.laborat}</li>
                                </ul>
                            </div>
                        `;
                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);
            });
    });
</script>
@endpush
