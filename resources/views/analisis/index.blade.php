@extends('layouts.app')

@section('title', 'Menu Analisis Lanjutan')
@section('page-title', 'Menu Analisis Lanjutan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Korelasi -->
    <a href="{{ route('analisis.korelasi') }}" class="block bg-white rounded-lg shadow-md p-6 card-hover border-t-4 border-blue-500 text-center">
        <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Analisis Korelasi</h3>
        <p class="text-sm text-gray-600">Analisis hubungan statistik antar variabel kesehatan (seperti Faskes vs Stunting).</p>
    </a>

    <!-- Klastering -->
    <a href="{{ route('analisis.klaster') }}" class="block bg-white rounded-lg shadow-md p-6 card-hover border-t-4 border-purple-500 text-center">
        <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Pemetaan Klaster</h3>
        <p class="text-sm text-gray-600">Pengelompokan wilayah berdasarkan tingkat kerawanan dan karakteristik kesehatan.</p>
    </a>

    <!-- Prediksi -->
    <a href="{{ route('analisis.prediksi') }}" class="block bg-white rounded-lg shadow-md p-6 card-hover border-t-4 border-orange-500 text-center">
        <div class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Prediksi Tren</h3>
        <p class="text-sm text-gray-600">Peramalan kasus penyakit atau stunting di masa mendatang menggunakan regresi.</p>
    </a>

    <!-- Spasial -->
    <a href="{{ route('analisis.spasial') }}" class="block bg-white rounded-lg shadow-md p-6 card-hover border-t-4 border-green-500 text-center">
        <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Analisis Spasial</h3>
        <p class="text-sm text-gray-600">Visualisasi data kesehatan secara geografis untuk melihat sebaran penyakit.</p>
    </a>
</div>
@endsection
