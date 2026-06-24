@extends('layouts.app')

@section('title', 'Analisis Korelasi')
@section('page-title', 'Analisis Korelasi Faktor Kesehatan')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900">Korelasi Faktor terhadap Prevalensi Stunting</h3>
        <a href="{{ route('analisis.index') }}" class="text-sm text-red-600 hover:text-red-800">Kembali ke Menu</a>
    </div>

    <?php if ($status === 'success'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <p class="text-sm text-gray-600 mb-4">
                    Hasil ini didapat dari pemrosesan statistik menggunakan algoritma Pearson Correlation melalui Python Service. 
                    Ukuran Sampel: <strong>{{ $sample_size }} Kecamatan</strong>.
                </p>
                
                <div class="space-y-4">
                    <?php foreach ($results as $var => $stat): ?>
                        <div class="border rounded-lg p-4 <?php echo ($stat['correlation'] < 0) ? 'bg-green-50' : 'bg-red-50'; ?>">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 uppercase">{{ $var }}</h4>
                                    <p class="text-xs text-gray-600">P-Value: {{ $stat['p_value'] }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-semibold <?php echo ($stat['correlation'] < 0) ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
                                    r = {{ $stat['correlation'] }}
                                </span>
                            </div>
                            <p class="text-sm mt-2">
                                <strong>Interpretasi:</strong> Terdapat korelasi yang <strong>{{ $stat['interpretasi'] }}</strong> antara jumlah {{ $var }} dengan angka Stunting.
                                <?php if ($stat['correlation'] < 0): ?>
                                    Semakin banyak {{ $var }}, semakin rendah angka stunting.
                                <?php else: ?>
                                    Terdapat hubungan searah antara {{ $var }} dengan angka stunting.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="bg-gray-50 border rounded-lg p-4 flex items-center justify-center min-h-[300px]">
                <canvas id="scatterChart"></canvas>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Gagal Memuat Analisis</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

@push('scripts')
<?php if ($status === 'success'): ?>
<script>
    const data = <?php echo json_encode($data); ?>;
    
    // Scatter Plot Nakes vs Stunting
    const scatterData = data.map(item => ({
        x: item.nakes,
        y: item.stunting,
        kecamatan: item.nama
    }));
    
    const ctx = document.getElementById('scatterChart').getContext('2d');
    new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Nakes vs Stunting',
                data: scatterData,
                backgroundColor: 'rgba(220, 38, 38, 0.6)',
                borderColor: 'rgba(220, 38, 38, 1)',
                borderWidth: 1,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const point = context.raw;
                            return `${point.kecamatan}: Nakes(${point.x}), Stunting(${point.y}%)`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Scatter Plot Nakes vs Stunting'
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Jumlah Tenaga Kesehatan' }
                },
                y: {
                    title: { display: true, text: 'Prevalensi Stunting (%)' }
                }
            }
        }
    });
</script>
<?php endif; ?>
@endpush
@endsection
