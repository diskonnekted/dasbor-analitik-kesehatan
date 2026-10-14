@extends('layouts.app')

@section('title', 'Data Cakupan Imunisasi')
@section('page-title', 'Manajemen Data Cakupan Imunisasi')

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
            Grafik Target vs Realisasi Imunisasi Dasar Lengkap
        </h3>
        <div class="h-80">
            <canvas id="imunisasiChart" role="img" aria-label="Grafik target versus realisasi imunisasi dasar lengkap"></canvas>
        </div>
    </div>

    <!-- Tabel -->
    <div class="md-card overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-md-outline/10 px-6 py-5">
            <h3 class="md-card-title">Daftar Cakupan Imunisasi</h3>
            <span class="md-badge"><i data-lucide="database" class="h-3 w-3" aria-hidden="true"></i> Data Open Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="md-table">
                <thead>
                    <tr class="border-b border-md-outline/20">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'Target Bayi', 'BCG', 'Polio 4', 'Campak MR', 'Lengkap (%)'] as $col)
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
                            <td class="bg-md-secondary-container/50 text-center">{{ $item->target_bayi }}</td>
                            <td class="text-center">{{ $item->bcg }}</td>
                            <td class="text-center">{{ $item->polio4 }}</td>
                            <td class="text-center">{{ $item->campak_mr1 }}</td>
                            <td class="bg-md-secondary-container/70 text-center font-medium">{{ $item->persentase_dasar_lengkap }}%</td>
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
    new Chart(document.getElementById('imunisasiChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                { label: 'Realisasi Imunisasi Lengkap', data: @json($realisasiData), backgroundColor: '#6750A4', borderRadius: 8, maxBarThickness: 28 },
                { label: 'Target Bayi', data: @json($targetData), backgroundColor: '#E7E0EC', borderRadius: 8, maxBarThickness: 28 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { boxWidth: 10, usePointStyle: true } } },
            scales: {
                x: { grid: { display: false }, border: { width: 0 }, ticks: { maxRotation: 45 } },
                y: { beginAtZero: true, border: { width: 0 } }
            }
        }
    });
});
</script>
@endpush
