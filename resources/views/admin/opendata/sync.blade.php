<!-- resources/views/admin/opendata/sync.blade.php -->
@extends('layouts.app')

@section('title', 'Sync OpenData')
@section('page-title', 'Sync Data dari OpenData Banjarnegara')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Sync Data Otomatis</h3>
            <p class="text-sm text-gray-600">
                Ambil data terbaru dari OpenData Banjarnegara dan import ke database aplikasi.
                Data akan di-sync otomatis setiap hari jam 02:00 WIB.
            </p>
        </div>
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <form action="{{ route('admin.opendata.sync') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Dataset (Opsional)
                </label>
                <select name="dataset" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">-- Sync Semua Dataset --</option>
                    <option value="tenaga_kesehatan">Jumlah Tenaga Kesehatan</option>
                    <option value="faskes">Jumlah Faskes (RS, Puskesmas, dll)</option>
                    <option value="kasus_penyakit">Jumlah Kasus Penyakit</option>
                    <option value="kematian_ibu_bayi">Kematian Ibu dan Bayi</option>
                    <option value="persalinan">Persalinan menurut Penolong</option>
                    <option value="pasien_rawat">Pasien Rawat Jalan/Inap</option>
                </select>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-md transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Sync Sekarang
                </button>
                
                <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md transition">
                    Batal
                </a>
            </div>
        </form>
        
        <div class="mt-8 pt-6 border-t">
            <h4 class="text-md font-bold text-gray-900 mb-3">Informasi Dataset</h4>
            <div class="space-y-2 text-sm text-gray-600">
                <p><strong>Sumber:</strong> OpenData Banjarnegara (Dinas Kesehatan)</p>
                <p><strong>Update Terakhir:</strong> {{ now()->format('d F Y H:i') }} WIB</p>
                <p><strong>Frekuensi Sync:</strong> Harian (02:00 WIB) dan Mingguan (Senin, 03:00 WIB)</p>
                <p><strong>Total Dataset:</strong> 8 dataset kesehatan</p>
            </div>
        </div>
    </div>
</div>
@endsection
```
