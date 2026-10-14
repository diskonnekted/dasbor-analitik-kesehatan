@extends('layouts.app')

@section('title', 'Data Fasilitas Kesehatan')
@section('page-title', 'Manajemen Data Fasilitas Kesehatan')

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
            Grafik Sebaran Fasilitas Kesehatan per Kecamatan
        </h3>
        <div class="h-80">
            <canvas id="faskesChart" role="img" aria-label="Grafik sebaran fasilitas kesehatan per kecamatan"></canvas>
        </div>
    </div>

    <!-- Tren Tahunan -->
    <div class="md-card p-6 md:p-8">
        <h2 class="md-card-title mb-6">
            <i data-lucide="line-chart" class="w-4 h-4 text-md-primary"></i>
            Tren Jumlah Fasilitas Kesehatan Per Tahun
        </h2>
        <div class="h-[300px]">
            <canvas id="trendChart" role="img" aria-label="Grafik tren jumlah fasilitas kesehatan per tahun"></canvas>
        </div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Fasilitas Kesehatan</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Data Open Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'RS Umum', 'Puskesmas', 'Klinik', 'Posyandu', 'Poskesdes', 'Total'] as $col)
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
                            <td class="text-center">{{ $item->rs_umum }}</td>
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->puskesmas }}</td>
                            <td class="text-center">{{ $item->klinik }}</td>
                            <td class="text-center">{{ $item->posyandu }}</td>
                            <td class="text-center">{{ $item->poskesdes }}</td>
                            <td class="bg-md-secondary-container/50 text-center font-medium">{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-md-on-surface-variant">
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
    new Chart(document.getElementById('faskesChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                { label: 'Rumah Sakit', data: @json($rsData), backgroundColor: '#B69DF8' },
                { label: 'Puskesmas', data: @json($puskesmasData), backgroundColor: '#6750A4' },
                { label: 'Klinik', data: @json($klinikData), backgroundColor: '#7D5260' }
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: @json($yearly->pluck('tahun')),
                    datasets: [{
                        label: 'Total Fasilitas',
                        data: @json($yearly->pluck('total')),
                        borderColor: '#6750A4',
                        backgroundColor: 'rgba(103, 80, 164, 0.12)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#6750A4'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
@endpush
