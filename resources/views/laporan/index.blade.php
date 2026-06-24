@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Pusat Laporan Kesehatan')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Modul Laporan</h3>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <p class="text-gray-600 mb-6">Pilih jenis laporan yang ingin Anda generate atau unduh.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card: Laporan Stunting -->
            <div class="border rounded-lg p-5 hover:shadow-lg transition">
                <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan Stunting Tahunan</h4>
                <p class="text-sm text-gray-500 mb-4">Rekapitulasi data prevalensi stunting dari seluruh kecamatan untuk tahun berjalan.</p>
                <div class="flex gap-2">
                    <a href="{{ route('laporan.generate', ['type' => 'stunting']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">Lihat HTML</a>
                    <a href="{{ route('laporan.export.excel', ['type' => 'stunting']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">Export Excel</a>
                    <a href="{{ route('laporan.export.pdf', ['type' => 'stunting']) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">Cetak PDF</a>
                </div>
            </div>
            
            <!-- Card: Laporan Penyakit -->
            <div class="border rounded-lg p-5 hover:shadow-lg transition">
                <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan Wabah & Penyakit</h4>
                <p class="text-sm text-gray-500 mb-4">Rincian laporan persebaran 10 jenis penyakit terbanyak (Malaria, TB, Diare, dll).</p>
                <div class="flex gap-2">
                    <a href="{{ route('laporan.generate', ['type' => 'penyakit']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">Lihat HTML</a>
                    <a href="{{ route('laporan.export.excel', ['type' => 'penyakit']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">Export Excel</a>
                    <a href="{{ route('laporan.export.pdf', ['type' => 'penyakit']) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">Cetak PDF</a>
                </div>
            </div>
            
            <!-- Card: Laporan Fasilitas Kesehatan -->
            <div class="border rounded-lg p-5 hover:shadow-lg transition">
                <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan Fasilitas Kesehatan</h4>
                <p class="text-sm text-gray-500 mb-4">Daftar lengkap Puskesmas, Rumah Sakit, dan Klinik per kecamatan.</p>
                <div class="flex gap-2">
                    <a href="{{ route('laporan.generate', ['type' => 'faskes']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">Lihat HTML</a>
                    <a href="{{ route('laporan.export.excel', ['type' => 'faskes']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">Export Excel</a>
                    <a href="{{ route('laporan.export.pdf', ['type' => 'faskes']) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">Cetak PDF</a>
                </div>
            </div>

            <!-- Card: Laporan Tenaga Kesehatan -->
            <div class="border rounded-lg p-5 hover:shadow-lg transition">
                <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan Tenaga Kesehatan</h4>
                <p class="text-sm text-gray-500 mb-4">Jumlah ketersediaan Dokter, Perawat, dan Bidan di berbagai jenjang puskesmas.</p>
                <div class="flex gap-2">
                    <a href="{{ route('laporan.generate', ['type' => 'nakes']) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">Lihat HTML</a>
                    <a href="{{ route('laporan.export.excel', ['type' => 'nakes']) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">Export Excel</a>
                    <a href="{{ route('laporan.export.pdf', ['type' => 'nakes']) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium">Cetak PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
