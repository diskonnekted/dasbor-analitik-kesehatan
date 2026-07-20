@extends('layouts.app')

@section('title', 'Rasio Nakes vs Beban Penyakit')
@section('page-title', 'Rasio Tenaga Kesehatan vs Beban Penyakit')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-blue-400"></i>
                Workload Analysis
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Beban Kerja Tenaga Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Rasio jumlah kasus penyakit menular per satu tenaga kesehatan tahun <strong>{{ $tahun }}</strong>. Semakin tinggi rasio, semakin berat beban kerja nakes &mdash; indikasi kebutuhan penambahan/redistribusi SDM. Rata-rata kabupaten: <strong>{{ $rata_rasio }}</strong> kasus/nakes.
            </p>
        </div>

        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Chart -->
        <div class="lg:col-span-7 bg-white p-6 md:p-8 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] flex flex-col">
            <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i>
                Rasio Beban per Kecamatan
            </h2>
            <div class="flex-grow border-2 border-[#171717] p-4 bg-[#f8f8f6] shadow-[2px_2px_0px_0px_#171717] min-h-[420px]">
                <canvas id="rasioChart"></canvas>
            </div>
        </div>

        <!-- Tabel -->
        <div class="lg:col-span-5 bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
            <div class="bg-[#171717] p-4 flex items-center gap-2">
                <i data-lucide="list-ordered" class="w-4 h-4 text-blue-400"></i>
                <h2 class="text-sm font-serif font-black uppercase tracking-widest text-white">Peringkat Beban</h2>
            </div>
            <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0">
                        <tr class="bg-[#f1f1ef] border-b-2 border-[#171717] text-[10px] font-mono font-extrabold uppercase tracking-wider text-neutral-600">
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3 text-right">Nakes</th>
                            <th class="px-4 py-3 text-right">Kasus</th>
                            <th class="px-4 py-3 text-right">Rasio</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        @foreach ($rows as $r)
                            @php
                                $badge = match ($r['status']) {
                                    'Beban Berat' => 'bg-red-200 text-red-900 border-red-600',
                                    'Di Atas Rata-rata' => 'bg-amber-100 text-amber-900 border-amber-500',
                                    'Memadai' => 'bg-emerald-100 text-emerald-900 border-emerald-600',
                                    default => 'bg-neutral-100 text-neutral-600 border-neutral-400',
                                };
                            @endphp
                            <tr class="border-b border-neutral-200 hover:bg-[#f8f8f6]">
                                <td class="px-4 py-3 font-bold text-[#171717]">{{ $r['kecamatan'] }}</td>
                                <td class="px-4 py-3 text-right text-neutral-700">{{ number_format($r['nakes']) }}</td>
                                <td class="px-4 py-3 text-right text-neutral-700">{{ number_format($r['beban']) }}</td>
                                <td class="px-4 py-3 text-right font-black text-[#171717]">{{ $r['rasio'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase tracking-wider border-2 {{ $badge }}">
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
        const rataRasio = {{ $rata_rasio }};
        const labels = rows.map(r => r.kecamatan);
        const values = rows.map(r => r.rasio);
        const colors = rows.map(r => {
            if (r.status === 'Beban Berat') return '#b91c1c';
            if (r.status === 'Di Atas Rata-rata') return '#d97706';
            if (r.status === 'Memadai') return '#047857';
            return '#a3a3a3';
        });

        const ctx = document.getElementById('rasioChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kasus per Nakes',
                    data: values,
                    backgroundColor: colors,
                    borderColor: '#171717',
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#171717',
                        titleFont: { family: 'Courier New', weight: 'bold' },
                        bodyFont: { family: 'Courier New' }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#e5e5d8' },
                        border: { width: 2, color: '#171717' },
                        title: { display: true, text: 'RASIO KASUS / NAKES', font: { family: 'Courier New', weight: 'bold', size: 10 } },
                        ticks: { font: { family: 'Courier New', size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        border: { width: 2, color: '#171717' },
                        ticks: { font: { family: 'Courier New', size: 9 } }
                    }
                }
            }
        });
    });
</script>
@endpush
