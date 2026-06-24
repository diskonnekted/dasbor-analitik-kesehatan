@extends('layouts.app')

@section('title', 'Peta Sebaran Posyandu')
@section('page-title', 'Peta Sebaran Posyandu & Kader')

@section('content')
<div class="space-y-6">
    <!-- Map Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Peta Spasial Posyandu</h3>
            <p class="text-sm text-gray-500">Visualisasi geografis sebaran jumlah posyandu dan kader per kecamatan.</p>
        </div>
        <div id="map" class="h-96 w-full z-0"></div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Daftar Detail Posyandu & Kader</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                Data OpenData
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Puskesmas</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Posyandu</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Kader</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($data as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $item->kecamatan->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->puskesmas }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->tahun }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">{{ number_format($item->jumlah_posyandu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">{{ number_format($item->jumlah_kader, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-4 text-gray-500">Belum ada data. Lakukan sinkronisasi terlebih dahulu.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($data->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init map coordinates for Banjarnegara
        const map = L.map('map').setView([-7.3980, 109.6965], 11);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const mapData = @json($mapData);

        mapData.forEach(kec => {
            if(kec.latitude && kec.longitude) {
                const radius = Math.max(10, Math.min(30, (kec.jumlah_posyandu / 100) * 20));
                
                L.circleMarker([kec.latitude, kec.longitude], {
                    radius: radius,
                    fillColor: '#ef4444', // Red
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.7
                }).addTo(map)
                .bindPopup(`
                    <div class="text-center">
                        <strong class="block text-lg border-b pb-1 mb-1">${kec.nama}</strong>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase">Posyandu</span>
                                <span class="block text-xl font-bold text-blue-600">${kec.jumlah_posyandu}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase">Kader</span>
                                <span class="block text-xl font-bold text-green-600">${kec.jumlah_kader}</span>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush
@endsection
