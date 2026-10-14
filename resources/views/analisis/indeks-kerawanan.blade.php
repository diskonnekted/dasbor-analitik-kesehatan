@extends('layouts.app')

@section('title', 'Indeks Kerawanan Kesehatan')
@section('page-title', 'Indeks Kerawanan Kesehatan')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <span class="md-badge mb-4">
                <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                Composite Risk Index
            </span>
            <h1 class="text-3xl font-medium text-md-on-surface tracking-tight mb-2">Indeks Kerawanan Kesehatan</h1>
            <p class="text-sm text-md-on-surface-variant leading-relaxed">
                Skor gabungan 0&ndash;100 (tahun <strong>{{ $tahun }}</strong>) dari tiga indikator ternormalisasi: prevalensi stunting (40%), beban penyakit per nakes (35%), dan kelangkaan faskes (25%). Semakin tinggi skor, semakin prioritas untuk intervensi.
            </p>
        </div>
        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full bg-md-secondary-container text-md-on-secondary-container text-sm font-medium hover:shadow-md transition-shadow self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    @php
        $tinggi = collect($rows)->where('kategori', 'Rawan Tinggi')->count();
        $sedang = collect($rows)->where('kategori', 'Rawan Sedang')->count();
        $rendah = collect($rows)->where('kategori', 'Rawan Rendah')->count();
        $tones = [
            'Rawan Tinggi' => ['bg-red-50 text-red-800', 'text-red-700', 'alert-octagon'],
            'Rawan Sedang' => ['bg-amber-50 text-amber-800', 'text-amber-700', 'alert-triangle'],
            'Rawan Rendah' => ['bg-emerald-50 text-emerald-800', 'text-emerald-700', 'shield-check'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach ([['Rawan Tinggi', $tinggi], ['Rawan Sedang', $sedang], ['Rawan Rendah', $rendah]] as [$label, $count])
            @php $tone = $tones[$label]; @endphp
            <div class="md-card p-6 {{ $tone[0] }} rounded-[28px]">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium uppercase tracking-wider opacity-70">{{ $label }}</span>
                    <i data-lucide="{{ $tone[2] }}" class="w-5 h-5 {{ $tone[1] }}"></i>
                </div>
                <p class="text-4xl font-normal mt-2">{{ $count }}</p>
                <p class="text-[11px] opacity-60 mt-1">Kecamatan</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 md-card p-6 md:p-8 flex flex-col">
            <h2 class="md-card-title mb-6">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-md-primary"></i>
                Peringkat Indeks Kerawanan
            </h2>
            <div class="flex-grow relative min-h-[420px]">
                <canvas id="indeksChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-5 md-card overflow-hidden">
            <div class="flex items-center gap-2 p-6 pb-4">
                <i data-lucide="table" class="w-4 h-4 text-md-primary"></i>
                <h2 class="md-card-title">Rincian Skor</h2>
            </div>
            <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
                <table class="md-table">
                    <thead class="sticky top-0 bg-md-surface-container z-10">
                        <tr class="border-b border-md-outline/20">
                            <th>#</th>
                            <th>Kecamatan</th>
                            <th class="text-right">Indeks</th>
                            <th class="text-center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-md-outline/10">
                        @foreach ($rows as $i => $r)
                            @php $tone = $tones[$r['kategori']] ?? $tones['Rawan Rendah']; @endphp
                            <tr class="hover:bg-md-surface-container/60 transition-colors">
                                <td class="text-md-on-surface-variant">{{ $i + 1 }}</td>
                                <td class="font-medium">{{ $r['kecamatan'] }}</td>
                                <td class="text-right font-medium">{{ $r['indeks'] }}</td>
                                <td class="text-center">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-medium {{ $tone[0] }}">
                                        {{ $r['kategori'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="md-insight">
        <i data-lucide="info" class="w-5 h-5 mt-0.5"></i>
        <ul class="text-sm space-y-2">
            <li>Tiap indikator dinormalisasi (min-max) ke skala 0&ndash;1 antar kecamatan.</li>
            <li>Faskes diinversi (makin sedikit faskes = makin rawan).</li>
            <li>Indeks = (Stunting &times; 0,40) + (Beban/Nakes &times; 0,35) + (Kelangkaan Faskes &times; 0,25), lalu &times;100.</li>
            <li>Kategori: &ge;66 Tinggi, 33&ndash;65 Sedang, &lt;33 Rendah.</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = @json($rows);
        const labels = rows.map(r => r.kecamatan);
        const values = rows.map(r => r.indeks);
        const colors = rows.map(r => {
            if (r.kategori === 'Rawan Tinggi') return '#B3261E';
            if (r.kategori === 'Rawan Sedang') return '#7D5260';
            return '#6750A4';
        });

        new Chart(document.getElementById('indeksChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Indeks Kerawanan',
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
                        min: 0, max: 100,
                        grid: { color: 'rgba(121,116,126,0.2)' },
                        border: { width: 0 },
                        title: { display: true, text: 'Skor Indeks (0-100)', font: { family: "'Roboto', sans-serif", size: 10 } }
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
