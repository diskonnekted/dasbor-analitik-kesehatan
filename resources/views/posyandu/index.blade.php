@extends('layouts.app')

@section('title', 'Peta Sebaran Posyandu')
@section('page-title', 'Peta Sebaran Posyandu & Kader')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-[32px] bg-md-surface-container p-8">
        <div class="pointer-events-none absolute -top-20 -right-16 h-64 w-64 rounded-full bg-md-primary/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative max-w-3xl">
            <span class="md-badge">
                <i data-lucide="map-pin" class="h-3.5 w-3.5" aria-hidden="true"></i>
                Healthcare Facilities Mapping
            </span>
            <h1 class="mt-4 text-3xl font-medium tracking-tight text-md-on-surface">Sebaran Posyandu &amp; Kader</h1>
            <p class="mt-2 text-sm leading-relaxed text-md-on-surface-variant">
                Pemetaan geografis posyandu aktif beserta jumlah kader kesehatan per kecamatan di seluruh wilayah Kabupaten Banjarnegara.
            </p>
        </div>
    </div>

    <!-- Peta -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">
                <i data-lucide="map" class="h-4.5 w-4.5 text-md-primary"></i>
                Peta Spasial Sebaran Posyandu
            </h3>
            <span class="md-badge"><i data-lucide="map-pin" class="h-3 w-3" aria-hidden="true"></i> Posyandu GIS</span>
        </div>
        <div id="map" class="h-[450px] w-full" style="z-index: 0;"></div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Detail Posyandu &amp; Kader</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Data Open Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        @foreach(['ID', 'Kecamatan', 'Puskesmas', 'Tahun', 'Jumlah Posyandu', 'Jumlah Kader'] as $col)
                            <th class="text-center">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b border-md-outline/10 transition-colors duration-200 hover:bg-md-primary/5">
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="font-medium">{{ $item->kecamatan->nama ?? '-' }}</td>
                            <td>{{ $item->puskesmas }}</td>
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="bg-md-secondary-container/50 text-center font-medium">{{ number_format($item->jumlah_posyandu, 0, ',', '.') }}</td>
                            <td class="bg-md-secondary-container/50 text-center font-medium">{{ number_format($item->jumlah_kader, 0, ',', '.') }}</td>
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
        const map = L.map('map').setView([-7.3980, 109.6965], 11);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        }).addTo(map);

        const mapData = @json($mapData);

        mapData.forEach(kec => {
            if(kec.latitude && kec.longitude) {
                const radius = Math.max(10, Math.min(30, (kec.jumlah_posyandu / 100) * 20));

                L.circleMarker([kec.latitude, kec.longitude], {
                    radius: radius,
                    fillColor: '#6750A4',
                    color: '#FFFFFF',
                    weight: 1.5,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map)
                .bindPopup(`
                    <div style="font-family: 'Roboto', sans-serif; font-size: 12px; color: #1C1B1F; min-width: 180px; padding: 2px;">
                        <strong style="display: block; border-bottom: 1px solid #E7E0EC; color: #6750A4; font-size: 13px; font-weight: 500; margin-bottom: 8px; padding-bottom: 6px;">${kec.nama}</strong>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-top: 8px;">
                            <div style="background: #E8DEF8; border-radius: 8px; padding: 6px; text-align: center;">
                                <span style="display:block; color:#49454F; font-size: 9px; letter-spacing: .04em;">POSYANDU</span>
                                <span style="font-size: 15px; font-weight: 500;">${kec.jumlah_posyandu}</span>
                            </div>
                            <div style="background: #E7E0EC; border-radius: 8px; padding: 6px; text-align: center;">
                                <span style="display:block; color:#49454F; font-size: 9px; letter-spacing: .04em;">KADER</span>
                                <span style="font-size: 15px; font-weight: 500;">${kec.jumlah_kader}</span>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    });
</script>
@endpush
