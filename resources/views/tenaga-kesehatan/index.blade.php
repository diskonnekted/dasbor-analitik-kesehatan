@extends('layouts.app')

@section('title', 'Data Tenaga Kesehatan')
@section('page-title', 'Manajemen Data Tenaga Kesehatan')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Insight / Analisis -->
    <div class="md-insight">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-md-primary/15 text-md-primary">
            <i data-lucide="info" class="h-5 w-5"></i>
        </div>
        <div>
            <h3 class="mb-1 text-sm font-medium">Insight & Analisis Cerdas</h3>
            <p class="text-xs leading-relaxed text-md-on-secondary-container/80">{!! $analysis !!}</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="md-card p-6">
        <h3 class="md-card-title mb-6">
            <i data-lucide="bar-chart-2" class="h-4.5 w-4.5 text-md-primary"></i>
            Grafik Komposisi Tenaga Kesehatan per Kecamatan
        </h3>
        <div class="h-80">
            <canvas id="nakesChart" role="img" aria-label="Grafik komposisi tenaga kesehatan per kecamatan"></canvas>
        </div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Tenaga Kesehatan</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Data Open Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'Dokter', 'Perawat', 'Bidan', 'Farmasi', 'Total'] as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b border-md-outline/10 transition-colors duration-200 hover:bg-md-primary/5">
                            <td>{{ $item->id }}</td>
                            <td class="font-medium">{{ $item->kecamatan_nama }}</td>
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->dokter_umum }}</td>
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->perawat }}</td>
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->bidan }}</td>
                            <td class="text-center">{{ $item->farmasi }}</td>
                            <td class="bg-md-secondary-container/70 text-center font-medium">{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-md-on-surface-variant">
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
    new Chart(document.getElementById('nakesChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                { label: 'Perawat', data: @json($perawatData), backgroundColor: '#B69DF8' },
                { label: 'Bidan', data: @json($bidanData), backgroundColor: '#7D5260' },
                { label: 'Dokter Umum', data: @json($dokterData), backgroundColor: '#6750A4' }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { boxWidth: 10, usePointStyle: true } } },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    border: { width: 0 },
                    ticks: { maxRotation: 45 }
                },
                y: { stacked: true, beginAtZero: true, border: { width: 0 } }
            }
        }
    });
});
</script>
@endpush
