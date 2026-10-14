@extends('layouts.app')

@section('title', 'Rasio Nakes vs Beban Penyakit')
@section('page-title', 'Rasio Tenaga Kesehatan vs Beban Penyakit')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="stethoscope" class="w-3.5 h-3.5"></i>
                Workload Analysis
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Beban Kerja Tenaga Kesehatan</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Rasio jumlah kasus penyakit menular per satu tenaga kesehatan tahun <strong>{{ $tahun }}</strong>. Semakin tinggi rasio, semakin berat beban kerja nakes &mdash; indikasi kebutuhan penambahan/redistribusi SDM. Rata-rata kabupaten: <strong>{{ $rata_rasio }}</strong> kasus/nakes.
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 md-card p-6 md:p-8 flex flex-col">
            <h2 class="md-card-title mb-6">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-md-primary"></i>
                Rasio Beban per Kecamatan
            </h2>
            <div class="flex-grow relative min-h-[420px]">
                <canvas id="rasioChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-5 md-card overflow-hidden">
            <div class="flex items-center gap-2 p-6 pb-4">
                <i data-lucide="list-ordered" class="w-4 h-4 text-md-primary"></i>
                <h2 class="md-card-title">Peringkat Beban</h2>
            </div>
            <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
                <table class="md-table">
                    <thead class="sticky top-0 bg-md-surface-container z-10">
                        <tr class="border-b border-md-outline/20">
                            <th>Kecamatan</th>
                            <th class="text-right">Nakes</th>
                            <th class="text-right">Kasus</th>
                            <th class="text-right">Rasio</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-md-outline/10">
                        @foreach ($rows as $r)
                            @php
                                $tones = [
                                    'Beban Berat' => 'bg-red-50 text-red-800',
                                    'Di Atas Rata-rata' => 'bg-amber-50 text-amber-800',
                                    'Memadai' => 'bg-emerald-50 text-emerald-800',
                                ];
                                $tone = $tones[$r['status']] ?? 'bg-md-surface-container-low text-md-on-surface-variant';
                            @endphp
                            <tr class="hover:bg-md-surface-container/60 transition-colors">
                                <td class="font-medium">{{ $r['kecamatan'] }}</td>
                                <td class="text-right text-md-on-surface-variant">{{ number_format($r['nakes']) }}</td>
                                <td class="text-right text-md-on-surface-variant">{{ number_format($r['beban']) }}</td>
                                <td class="text-right font-medium">{{ $r['rasio'] ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-medium {{ $tone }}">
                                        {{ $r['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = @json($rows);
        const labels = rows.map(r => r.kecamatan);
        const values = rows.map(r => r.rasio);
        const colors = rows.map(r => {
            if (r.status === 'Beban Berat') return '#B3261E';
            if (r.status === 'Di Atas Rata-rata') return '#7D5260';
            if (r.status === 'Memadai') return '#6750A4';
            return '#C4C1C8';
        });

        new Chart(document.getElementById('rasioChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kasus per Nakes',
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 6,
                    maxBarThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#49454F',
                        cornerRadius: 12,
                        padding: 12,
                        titleFont: { family: "'Roboto', sans-serif", weight: '500' },
                        bodyFont: { family: "'Roboto', sans-serif" }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(121,116,126,0.2)' },
                        border: { width: 0 },
                        title: { display: true, text: 'Rasio Kasus / Nakes', font: { family: "'Roboto', sans-serif", size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        border: { width: 0 },
                        ticks: { font: { family: "'Roboto', sans-serif", size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
