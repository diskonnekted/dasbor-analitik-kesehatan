@extends('layouts.app')

@section('title', 'Data Fasilitas Kesehatan')
@section('page-title', 'Manajemen Data Fasilitas Kesehatan')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Daftar Fasilitas Kesehatan</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
            Data OpenData
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <?php foreach(['ID', 'Kecamatan', 'Tahun', 'RS Umum', 'Puskesmas', 'Klinik', 'Posyandu', 'Poskesdes', 'Total'] as $col): ?>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $col }}</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($data as $item)
                <tr class="hover:bg-gray-50 transition">
                    <?php foreach([$item->id, $item->kecamatan_nama, $item->tahun, $item->rs_umum, $item->puskesmas, $item->klinik, $item->posyandu, $item->poskesdes, $item->total] as $val): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $val ?? '-' }}</td>
                    <?php endforeach; ?>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-4 text-gray-500">Belum ada data. Lakukan sinkronisasi terlebih dahulu.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($data->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $data->links() }}
    </div>
    @endif
</div>
@endsection
