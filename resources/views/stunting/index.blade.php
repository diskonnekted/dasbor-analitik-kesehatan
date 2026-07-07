@extends('layouts.app')

@section('title', 'Data Stunting & Gizi')
@section('page-title', 'Manajemen Data Stunting & Gizi')

@section('content')
<div class="space-y-8">
    <!-- Insight / Analysis Section (Neo-Brutalist) -->
    <div class="bg-blue-100 border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717] flex items-start gap-4">
        <div class="w-10 h-10 bg-white border-2 border-[#171717] shadow-[2px_2px_0px_0px_#171717] flex items-center justify-center flex-shrink-0">
            <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
        </div>
        <div>
            <h3 class="text-sm font-serif font-black uppercase text-[#171717] tracking-wider mb-1">Insight & Analisis Cerdas</h3>
            <p class="text-xs font-mono font-bold text-neutral-700 leading-relaxed">{!! $analysis !!}</p>
        </div>
    </div>

    <!-- Chart Section (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] p-6 shadow-[4px_4px_0px_0px_#171717]">
        <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717] mb-6 flex items-center gap-2 border-b-2 border-[#171717] pb-2">
            <i data-lucide="bar-chart-2" class="w-4.5 h-4.5 text-amber-600"></i>
            Grafik Sebaran Kasus Stunting & Gizi Buruk per Kecamatan
        </h3>
        <div class="h-80 border-2 border-[#171717] p-2 bg-[#f8f8f6]">
            <canvas id="stuntingChart"></canvas>
        </div>
    </div>

    <!-- Data Table Section (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
        <div class="px-6 py-5 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717]">Daftar Stunting & Gizi</h3>
            <span class="inline-block px-3 py-1 bg-amber-200 border-2 border-[#171717] text-amber-950 font-mono font-black text-[10px] shadow-[1.5px_1.5px_0px_0px_#171717]">
                DATA OPEN DATA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-b-2 border-[#171717] text-left text-xs font-mono font-bold">
                <thead>
                    <tr class="bg-neutral-50 border-b-2 border-[#171717]">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'Balita Diukur', 'Stunting', 'Gizi Buruk', 'Prevalensi (%)'] as $col)
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#171717]">
                    @forelse ($data as $item)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 border-r-2 border-[#171717]">{{ $item->id }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] uppercase font-serif font-black text-sm">{{ $item->kecamatan_nama }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->tahun }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->jumlah_balita_diukur }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center text-amber-700 bg-amber-50">{{ $item->jumlah_stunting }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center text-red-700 bg-red-50">{{ $item->jumlah_gizi_buruk }}</td>
                            <td class="px-6 py-4 text-center font-black text-sm bg-neutral-100">{{ $item->prevalensi_stunting }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-neutral-500">
                                Belum ada data. Lakukan sinkronisasi terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($data->hasPages())
            <div class="px-6 py-4 bg-[#f4f4f0] border-t-2 border-[#171717] font-mono">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stuntingChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Anak Stunting',
                    data: @json($stuntingData),
                    backgroundColor: '#f97316',
                    borderColor: '#171717',
                    borderWidth: 2,
                    borderRadius: 0
                },
                {
                    label: 'Anak Gizi Buruk',
                    data: @json($giziBurukData),
                    backgroundColor: '#ef4444',
                    borderColor: '#171717',
                    borderWidth: 2,
                    borderRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                }
            },
            scales: {
                x: { 
                    stacked: true,
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                },
                y: { 
                    stacked: true, 
                    beginAtZero: true,
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                }
            }
        }
    });
});
</script>
@endpush
