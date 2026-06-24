@extends('layouts.app')

@section('title', 'Pemetaan Klaster')
@section('page-title', 'Pemetaan Klaster Wilayah Stunting')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900">Hasil Klastering K-Means</h3>
        <a href="{{ route('analisis.index') }}" class="text-sm text-red-600 hover:text-red-800">Kembali ke Menu</a>
    </div>

    <?php if ($status === 'success'): ?>
        <p class="text-sm text-gray-600 mb-6">
            Hasil pengelompokan (clustering) menggunakan algoritma K-Means melalui Python Service. Wilayah dikelompokkan berdasarkan tingkat kerawanan.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <h4 class="font-bold text-red-800">Tinggi (Rawan)</h4>
                <p class="text-2xl font-bold text-red-600 mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Tinggi')->count() }}
                </p>
                <p class="text-xs text-red-600 mt-1">Kecamatan</p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <h4 class="font-bold text-yellow-800">Sedang (Waspada)</h4>
                <p class="text-2xl font-bold text-yellow-600 mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Sedang')->count() }}
                </p>
                <p class="text-xs text-yellow-600 mt-1">Kecamatan</p>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <h4 class="font-bold text-green-800">Rendah (Aman)</h4>
                <p class="text-2xl font-bold text-green-600 mt-2">
                    {{ collect($clusters)->where('kerawanan', 'Rendah')->count() }}
                </p>
                <p class="text-xs text-green-600 mt-1">Kecamatan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prevalensi Stunting (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Kerawanan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($clusters as $cluster): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cluster['nama'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($cluster['stunting'], 2) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($cluster['kerawanan'] == 'Tinggi'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tinggi</span>
                            <?php elseif ($cluster['kerawanan'] == 'Sedang'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sedang</span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Rendah</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Icon -->
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Gagal Memuat Klastering</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
@endsection
