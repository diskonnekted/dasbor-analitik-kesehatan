@extends('layouts.app')

@section('title', 'Data Kasus Penyakit')
@section('page-title', 'Manajemen Data Kasus Penyakit')

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
            <i data-lucide="bar-chart-2" class="w-4.5 h-4.5 text-red-600"></i>
            Grafik 5 Kasus Penyakit Menular Terbanyak
        </h3>
        <div class="h-80 border-2 border-[#171717] p-2 bg-[#f8f8f6]">
            <canvas id="penyakitChart"></canvas>
        </div>
    </div>

    <!-- Data Table Section (Neo-Brutalist) -->
    <div class="bg-white border-2 border-[#171717] shadow-[4px_4px_0px_0px_#171717] overflow-hidden">
        <div class="px-6 py-5 border-b-2 border-[#171717] bg-[#f4f4f0] flex justify-between items-center">
            <h3 class="text-sm font-serif font-black uppercase tracking-widest text-[#171717]">Daftar Kasus Penyakit</h3>
            <span class="inline-block px-3 py-1 bg-red-200 border-2 border-[#171717] text-red-950 font-mono font-black text-[10px] shadow-[1.5px_1.5px_0px_0px_#171717]">
                DATA OPEN DATA
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border-b-2 border-[#171717] text-left text-xs font-mono font-bold">
                <thead>
                    <tr class="bg-neutral-50 border-b-2 border-[#171717]">
                        @foreach(['ID', 'Kecamatan', 'Tahun', 'Malaria', 'TB Paru', 'Pneumonia', 'Kusta', 'Tetanus', 'Campak', 'Diare', 'DBD', 'HIV', 'IMS'] as $col)
                            <th class="px-6 py-4 border-r-2 border-[#171717] uppercase tracking-wider text-center">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-[#171717]">
                    @forelse ($data as $item)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->id }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] uppercase font-serif font-black text-sm">{{ $item->kecamatan_nama }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->tahun }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center bg-red-50 text-red-900">{{ $item->malaria }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->tb_paru }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->pneumonia }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->kusta }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->tetanus_neonatorum }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->campak }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->diare }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->dbd }}</td>
                            <td class="px-6 py-4 border-r-2 border-[#171717] text-center">{{ $item->hiv_baru }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->ims }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-12 text-center text-neutral-500">
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
    const ctx = document.getElementById('penyakitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Jumlah Kasus',
                data: @json($dataset),
                backgroundColor: [
                    '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16'
                ],
                borderColor: '#171717',
                borderWidth: 2,
                borderRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: '#e5e5d8' },
                    border: { width: 2, color: '#171717' },
                    ticks: { font: { family: 'Courier New', weight: 'bold' }, color: '#171717' }
                },
                y: { 
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
