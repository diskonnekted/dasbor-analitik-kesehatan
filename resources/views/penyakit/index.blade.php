@extends('layouts.app')

@section('title', 'Data Kasus Penyakit')
@section('page-title', 'Manajemen Data Kasus Penyakit')

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
            Grafik 5 Kasus Penyakit Menular Terbanyak
        </h3>
        <div class="h-80">
            <canvas id="penyakitChart" role="img" aria-label="Grafik 5 kasus penyakit menular terbanyak"></canvas>
        </div>
    </div>

    <!-- Tren Tahunan -->
    <div class="md-card p-6 md:p-8">
        <h2 class="md-card-title mb-6">
            <i data-lucide="line-chart" class="w-4 h-4 text-md-primary"></i>
            Tren Total Kasus Penyakit Per Tahun
        </h2>
        <div class="h-[300px]">
            <canvas id="trendChart" role="img" aria-label="Grafik tren total kasus penyakit per tahun"></canvas>
        </div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Kasus Penyakit</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Data Open Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'Malaria', 'TB Paru', 'Pneumonia', 'Kusta', 'Tetanus', 'Campak', 'Diare', 'DBD', 'HIV', 'IMS'] as $col)
                            <th class="text-center">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b border-md-outline/10 transition-colors duration-200 hover:bg-md-primary/5">
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="font-medium">{{ $item->kecamatan_nama }}</td>
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->malaria }}</td>
                            <td class="text-center">{{ $item->tb_paru }}</td>
                            <td class="text-center">{{ $item->pneumonia }}</td>
                            <td class="text-center">{{ $item->kusta }}</td>
                            <td class="text-center">{{ $item->tetanus_neonatorum }}</td>
                            <td class="text-center">{{ $item->campak }}</td>
                            <td class="text-center">{{ $item->diare }}</td>
                            <td class="text-center">{{ $item->dbd }}</td>
                            <td class="text-center">{{ $item->hiv_baru }}</td>
                            <td class="text-center">{{ $item->ims }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-12 text-center text-md-on-surface-variant">
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
    new Chart(document.getElementById('penyakitChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Jumlah Kasus',
                data: @json($dataset),
                backgroundColor: ['#6750A4', '#7D5260', '#B69DF8', '#625B71', '#D0A5B0'],
                borderRadius: 8,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { width: 0 } },
                y: { beginAtZero: true, border: { width: 0 } }
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
                        label: 'Total Kasus (5 Penyakit)',
                        data: @json($yearly->pluck('total')),
                        borderColor: '#7D5260',
                        backgroundColor: 'rgba(125, 82, 96, 0.12)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#7D5260'
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
