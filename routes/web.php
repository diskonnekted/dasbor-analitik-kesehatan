<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaskesController;
use App\Http\Controllers\TenagaKesehatanController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\StuntingController;
use App\Http\Controllers\AKIAKBController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes
Route::resource('faskes', FaskesController::class);
Route::resource('tenaga-kesehatan', TenagaKesehatanController::class);
Route::resource('penyakit', PenyakitController::class);
Route::resource('stunting', StuntingController::class);
Route::resource('aki-akb', AKIAKBController::class);
Route::resource('imunisasi', ImunisasiController::class);

// Analisis Routes
Route::prefix('analisis')->name('analisis.')->group(function () {
    Route::get('/', [AnalisisController::class, 'index'])->name('index');
    Route::get('/korelasi', [AnalisisController::class, 'korelasi'])->name('korelasi');
    Route::get('/klaster', [AnalisisController::class, 'klaster'])->name('klaster');
    Route::get('/prediksi', [AnalisisController::class, 'prediksi'])->name('prediksi');
    Route::get('/spasial', [AnalisisController::class, 'spasial'])->name('spasial');
});

// Laporan Routes
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/generate', [LaporanController::class, 'generate'])->name('generate');
    Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('export.pdf');
});

// Import Routes
Route::post('/import/faskes', [FaskesController::class, 'import'])->name('import.faskes');
Route::post('/import/tenaga-kesehatan', [TenagaKesehatanController::class, 'import'])->name('import.nakes');
Route::post('/import/penyakit', [PenyakitController::class, 'import'])->name('import.penyakit');
Route::post('/import/stunting', [StuntingController::class, 'import'])->name('import.stunting');

// API Routes (untuk Python Analytics Service)
Route::prefix('api')->group(function () {
    Route::get('/stats/summary', [Api\StatsController::class, 'summary']);
    Route::get('/stats/stunting', [Api\StatsController::class, 'stunting']);
    Route::get('/stats/penyakit', [Api\StatsController::class, 'penyakit']);
    Route::post('/predict/stunting', [Api\PredictController::class, 'stunting']);
    Route::post('/predict/penyakit', [Api\PredictController::class, 'penyakit']);
});

// Info JDN Route
Route::view('/info', 'info')->name('info');

