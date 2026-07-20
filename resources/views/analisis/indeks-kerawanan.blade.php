@extends('layouts.app')

@section('title', 'Indeks Kerawanan Kesehatan')
@section('page-title', 'Indeks Kerawanan Kesehatan')

@section('content')
<div class="space-y-8">
    <!-- Hero Header Banner -->
    <div class="bg-white border-2 border-[#171717] p-8 shadow-[4px_4px_0px_0px_#171717] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#171717] text-white text-[10px] font-mono font-bold tracking-widest uppercase mb-4 border border-[#171717] shadow-[2px_2px_0px_0px_#171717]">
                <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-orange-400"></i>
                Composite Risk Index
            </div>
            <h1 class="text-3xl font-serif font-black text-[#171717] uppercase tracking-tight mb-2">Indeks Kerawanan Kesehatan</h1>
            <p class="text-neutral-600 font-mono font-bold text-xs leading-relaxed">
                Skor gabungan 0&ndash;100 (tahun <strong>{{ $tahun }}</strong>) dari tiga indikator ternormalisasi: prevalensi stunting (40%), beban penyakit per nakes (35%), dan kelangkaan faskes (25%). Semakin tinggi skor, semakin prioritas untuk intervensi.
            </p>
        </div>

        <a href="{{ route('analisis.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-[#171717] font-mono font-black uppercase text-xs border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] hover:shadow-[4px_4px_0px_0px_#171717] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Menu</span>
        </a>
    </div>

    <!-- Ringkasan kategori -->
    @php
        $tinggi = collect($rows)->where('kategori', 'Rawan Tinggi')->count();
        $sedang = collect($rows)->where('kategori', 'Rawan Sedang')->count();
        $rendah = collect($rows)->where('kategori', 'Rawan Rendah')->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-red-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-red-700">Rawan Tinggi</span>
                <i data-lucide="alert-octagon" class="w-5 h-5 text-red-700"></i>
            </div>
            <p class="text-4xl font-mono font-black text-[#171717] mt-2">{{ $tinggi }}</p>
            <p class="text-[10px] font-mono font-bold text-neutral-500 uppercase">Kecamatan</p>
        </div>
        <div class="bg-amber-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-amber-700">Rawan Sedang</span>
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-700"></i>
            </div>
            <p class="text-4xl font-mono font-black text-[#171717] mt-2">{{ $sedang }}</p>
            <p class="text-[10px] font-mono font-bold text-neutral-500 uppercase">Kecamatan</p>
        </div>
        <div class="bg-emerald-50 border-2 border-[#171717] p-5 shadow-[4px_4px_0px_0px_#171717]">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono font-extrabold uppercase tracking-widest text-emerald-700">Rawan Rendah</span>
                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-700"></i>
            </div>
            <p class="text-4xl font-mono font-black text-[#171717] mt-2">{{ $rendah }}</p>
            <p class="text-[10px] font-mono font-bold text-neutral-500 uppercase">Kecamatan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Chart -->
        <div class="lg:col-span-7 bg-white p-6 md:p-8 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] flex flex-col">
            <h2 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-orange-600"></i>
                Peringkat Indeks Kerawanan
            </h2>
            <div class="flex-grow border-2 border-[#171717] p-4 bg-[#f8f8f6] shadow-[2px_2px_0px_0px_#171717] min-h-[420px]">
                <canvas id="indeksChart"></canvas>
            </div>
        </div>

        <!-- Tabel -->
        <div class="lg:col-span-5 bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
            <div class="bg-[#171717] p-4 flex items-center gap-2">
                <i data-lucide="table" class="w-4 h-4 text-orange-400"></i>
                <h2 class="text-sm font-serif font-black uppercase tracking-widest text-white">Rincian Skor</h2>
            </div>
            <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0">
                        <tr class="bg-[#f1f1ef] border-b-2 border-[#171717] text-[10px] font-mono font-extrabold uppercase tracking-wider text-neutral-600">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3 text-right">Stunting</th>
                            <th class="px-4 py-3 text-right">Indeks</th>
                            <th class="px-4 py-3 text-center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        @foreach ($rows as $i => $r)
                            @php
                                $badge = match ($r['kategori']) {
                                    'Rawan Tinggi' => 'bg-red-200 text-red-900 border-red-600',
                                    'Rawan Sedang' => 'bg-amber-100 text-amber-900 border-amber-500',
                                    default => 'bg-emerald-100 text-emerald-900 border-emerald-600',
                                };
                            @endphp
                            <tr class="border-b border-neutral-200 hover:bg-[#f8f8f6]">
                                <td class="px-4 py-3 font-black text-neutral-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-[#171717]">{{ $r['kecamatan'] }}</td>
                                <td class="px-4 py-3 text-right text-neutral-700">{{ $r['stunting'] }}%</td>
                                <td class="px-4 py-3 text-right font-black text-[#171717]">{{ $r['indeks'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase tracking-wider border-2 {{ $badge }}">
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

    <!-- Metodologi -->
    <div class="bg-white p-6 border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717]">
        <h4 class="text-[#171717] font-serif font-black text-xs uppercase tracking-widest mb-3 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
            <i data-lucide="info" class="w-4 h-4 text-orange-600"></i>
            Metodologi
        </h4>
        <ul class="text-[11px] space-y-2 font-mono font-bold text-neutral-600">
            <li>&bull; Tiap indikator dinormalisasi (min-max) ke skala 0&ndash;1 antar kecamatan.</li>
            <li>&bull; Faskes diinversi (makin sedikit faskes = makin rawan).</li>
            <li>&bull; Indeks = (Stunting &times; 0,40) + (Beban/Nakes &times; 0,35) + (Kelangkaan Faskes &times; 0,25), lalu &times;100.</li>
            <li>&bull; Kategori: &ge;66 Tinggi, 33&ndash;65 Sedang, &lt;33 Rendah.</li>
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
            if (r.kategori === 'Rawan Tinggi') return '#b91c1c';
            if (r.kategori === 'Rawan Sedang') return '#d97706';
            return '#047857';
        });

        const ctx = document.getElementById('indeksChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Indeks Kerawanan',
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
                        min: 0, max: 100,
                        grid: { color: '#e5e5d8' },
                        border: { width: 2, color: '#171717' },
                        title: { display: true, text: 'SKOR INDEKS (0-100)', font: { family: 'Courier New', weight: 'bold', size: 10 } },
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
