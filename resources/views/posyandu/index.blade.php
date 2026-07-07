@extends('layouts.app')

@section('title', 'Peta Sebaran Posyandu')
@section('page-title', 'Peta Sebaran Posyandu & Kader')

@section('content')
<div class="space-y-8">
    <!-- Top Hero Banner (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-400"></i>
                Healthcare Facilities mapping
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Sebaran Posyandu & Kader</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Pemetaan geografis posyandu aktif beserta jumlah kader kesehatan per kecamatan di seluruh wilayah Kabupaten Banjarnegara.
            </p>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
        <div class="px-6 py-4 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] flex items-center gap-2">
                <i data-lucide="map" class="w-4.5 h-4.5 text-indigo-600"></i>
                Peta Spasial Sebaran Posyandu
            </h3>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border-2 border-[#171717] shadow-[1px_1px_0px_0px_#171717] text-[10px] font-mono font-bold tracking-wider text-indigo-700 uppercase">
                Posyandu GIS
            </span>
        </div>
        
        <div id="map" style="height: 450px; width: 100%; z-index: 0;"></div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
        <div class="px-6 py-5 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717]">Daftar Detail Posyandu & Kader</h3>
            <span class="inline-block px-3 py-1 bg-indigo-200 border-2 border-[#171717] text-indigo-950 font-mono font-black text-[10px] shadow-[1.5px_1.5px_0px_0px_#171717]">
                DATA OPEN DATA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-b-2 border-[#171717] text-left text-xs font-mono font-bold">
                <thead>
                    <tr class="bg-neutral-50 border-b-2 border-[#171717]">
                        @foreach(['ID', 'Kecamatan', 'Puskesmas', 'Tahun', 'Jumlah Posyandu', 'Jumlah Kader'] as $col)
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#171717]">
                    @forelse ($data as $item)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->id }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] uppercase font-serif font-black text-sm">{{ $item->kecamatan->nama ?? '-' }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] font-bold">{{ $item->puskesmas }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->tahun }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center font-black text-sm text-blue-700 bg-blue-50">{{ number_format($item->jumlah_posyandu, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center font-black text-sm text-emerald-700 bg-emerald-50">{{ number_format($item->jumlah_kader, 0, ',', '.') }}</td>
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
        const map = L.map('map').setView([-7.3980, 109.6965], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const mapData = @json($mapData);

        mapData.forEach(kec => {
            if(kec.latitude && kec.longitude) {
                const radius = Math.max(10, Math.min(30, (kec.jumlah_posyandu / 100) * 20));
                
                L.circleMarker([kec.latitude, kec.longitude], {
                    radius: radius,
                    fillColor: '#8b5cf6', // Violet/Indigo
                    color: '#171717',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map)
                .bindPopup(`
                    <div style="font-family: 'Courier New', monospace; font-size: 11px; font-weight: bold; color: #171717; min-width: 170px; padding: 2px;">
                        <strong style="font-size: 13px; font-weight: 900; text-transform: uppercase; display:block; border-bottom: 2px solid #171717; padding-bottom: 4px; mb-4;">${kec.nama}</strong>
                        <div style="display: grid; grid-template-cols: 1fr 1fr; gap: 6px; margin-top: 8px;">
                            <div style="background: #eff6ff; padding: 4px; border: 1px solid #171717; text-align: center;">
                                <span style="font-size: 8px; display:block; color:#2563eb;">POSYANDU</span>
                                <span style="font-size: 15px; font-weight:900;">${kec.jumlah_posyandu}</span>
                            </div>
                            <div style="background: #ecfdf5; padding: 4px; border: 1px solid #171717; text-align: center;">
                                <span style="font-size: 8px; display:block; color:#059669;">KADER</span>
                                <span style="font-size: 15px; font-weight:900;">${kec.jumlah_kader}</span>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush
