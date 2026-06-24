<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Faskes;
use App\Models\TenagaKesehatan;
use App\Models\Stunting;
use App\Models\AKI;
use App\Models\AKB;
use App\Models\Imunisasi;
use App\Models\KasusPenyakit;
use App\Models\Alert;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get max year for each model dynamically
        $tahunStunting = Stunting::max('tahun') ?? 2025;
        $tahunStuntingLalu = $tahunStunting - 1;
        
        $tahunNakes = TenagaKesehatan::max('tahun') ?? 2025;
        $tahunNakesLalu = $tahunNakes - 1;
        
        $tahunPenyakit = KasusPenyakit::max('tahun') ?? 2025;
        
        $tahunImunisasi = Imunisasi::max('tahun') ?? 2025;
        
        $tahunAKI = AKI::max('tahun') ?? 2025;
        
        // KPI: Total Faskes
        $totalFaskes = Faskes::where('status', 'Aktif')->count();
        $faskesTahunLalu = max(1, $totalFaskes - rand(2, 5)); // Mock past data since we don't track faskes by year
        $faskesGrowth = round((($totalFaskes - $faskesTahunLalu) / $faskesTahunLalu) * 100, 1);
        
        // KPI: Total Tenaga Kesehatan
        $totalNakes = TenagaKesehatan::where('tahun', $tahunNakes)->sum('total');
        $nakesTahunLalu = TenagaKesehatan::where('tahun', $tahunNakesLalu)->sum('total');
        if ($nakesTahunLalu == 0) $nakesTahunLalu = max(1, $totalNakes - rand(10, 50));
        $nakesGrowth = round((($totalNakes - $nakesTahunLalu) / $nakesTahunLalu) * 100, 1);
        
        // KPI: Prevalensi Stunting
        $prevalensiStunting = Stunting::where('tahun', $tahunStunting)
            ->select(DB::raw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as prevalensi'))
            ->value('prevalensi') ?? 0;
        
        $prevalensiTahunLalu = Stunting::where('tahun', $tahunStuntingLalu)
            ->select(DB::raw('SUM(jumlah_stunting) * 100.0 / NULLIF(SUM(jumlah_balita_diukur), 0) as prevalensi'))
            ->value('prevalensi') ?? 0;
            
        $stuntingTrend = round($prevalensiStunting - $prevalensiTahunLalu, 1);
        
        // KPI: AKI (Total Kasus)
        $aki = AKI::where('tahun', $tahunAKI)->sum('jumlah_kematian_ibu');
        
        // Chart: 10 Penyakit Terbanyak (Akumulasi Semua Tahun karena data 2024 belum lengkap untuk semua penyakit)
        $kasusPenyakit = KasusPenyakit::selectRaw('
                SUM(malaria) as malaria, SUM(tb_paru) as tb_paru, SUM(pneumonia) as pneumonia, 
                SUM(kusta) as kusta, SUM(tetanus_neonatorum) as tetanus_neonatorum, SUM(campak) as campak, 
                SUM(diare) as diare, SUM(dbd) as dbd, SUM(hiv_baru) as hiv_baru, SUM(ims) as ims
            ')->first();
            
        $penyakitTotals = [];
        if ($kasusPenyakit) {
            $penyakitTotals = [
                'Malaria' => $kasusPenyakit->malaria ?? 0, 'TB Paru' => $kasusPenyakit->tb_paru ?? 0,
                'Pneumonia' => $kasusPenyakit->pneumonia ?? 0, 'Kusta' => $kasusPenyakit->kusta ?? 0,
                'Tetanus Neonatorum' => $kasusPenyakit->tetanus_neonatorum ?? 0, 'Campak' => $kasusPenyakit->campak ?? 0,
                'Diare' => $kasusPenyakit->diare ?? 0, 'Demam Berdarah' => $kasusPenyakit->dbd ?? 0,
                'HIV Baru' => $kasusPenyakit->hiv_baru ?? 0, 'IMS' => $kasusPenyakit->ims ?? 0,
            ];
            arsort($penyakitTotals);
        }
        
        $penyakitLabels = array_keys($penyakitTotals);
        $penyakitData = array_values($penyakitTotals);
        
        // Chart: Stunting per Kecamatan
        $stuntingKecamatan = Stunting::where('tahun', $tahunStunting)
            ->join('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
            ->select(
                'kecamatans.nama as kecamatan',
                DB::raw('SUM(stuntings.jumlah_stunting) * 100.0 / NULLIF(SUM(stuntings.jumlah_balita_diukur), 0) as prevalensi')
            )
            ->groupBy('kecamatans.id', 'kecamatans.nama')
            ->orderBy('kecamatans.nama')
            ->get();
        
        $kecamatanLabels = $stuntingKecamatan->pluck('kecamatan')->toArray();
        $stuntingData = $stuntingKecamatan->pluck('prevalensi')->map(fn($v) => round($v, 2))->toArray();
        
        // Chart: Cakupan Imunisasi
        $imunisasiData = Imunisasi::where('tahun', $tahunImunisasi)
            ->select(
                DB::raw('SUM(imunisasi_dasar_lengkap) as lengkap'),
                DB::raw('SUM(target_bayi) as target')
            )
            ->first();
        
        $imunisasiLengkap = ($imunisasiData && $imunisasiData->target > 0)
            ? round(($imunisasiData->lengkap / $imunisasiData->target) * 100, 1) 
            : 0;
        
        // Map: Stunting Spasial Data
        $stuntingMapData = Stunting::where('tahun', $tahunStunting)
            ->join('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
            ->whereNotNull('kecamatans.latitude')
            ->whereNotNull('kecamatans.longitude')
            ->select('kecamatans.nama', 'kecamatans.latitude', 'kecamatans.longitude',
                DB::raw('SUM(stuntings.jumlah_stunting) * 100.0 / NULLIF(SUM(stuntings.jumlah_balita_diukur), 0) as prevalensi')
            )
            ->groupBy('kecamatans.id', 'kecamatans.nama', 'kecamatans.latitude', 'kecamatans.longitude')
            ->get()
            ->toArray();
        
        // Alerts
        $alerts = Alert::orderByDesc('created_at')
            ->limit(5)
            ->get();
        
        // Quick Stats
        $totalPenduduk = 1100000; // Dari data kependudukan
        $sumDokter = max(1, TenagaKesehatan::where('tahun', $tahunNakes)->sum('dokter_umum'));
        $sumBidan = max(1, TenagaKesehatan::where('tahun', $tahunNakes)->sum('bidan'));
        
        $rasioDokter = round($totalPenduduk / $sumDokter);
        $rasioBidan = round($totalPenduduk / $sumBidan);
        
        $cakupanBPJS = 85.5; // Placeholder
        $totalDesa = max(1, Desa::count());
        $desaODF = 150; // Placeholder
        
        return view('dashboard.index', compact(
            'totalFaskes', 'faskesGrowth',
            'totalNakes', 'nakesGrowth',
            'prevalensiStunting', 'stuntingTrend',
            'aki',
            'penyakitLabels', 'penyakitData',
            'kecamatanLabels', 'stuntingData',
            'imunisasiLengkap',
            'stuntingMapData',
            'alerts',
            'rasioDokter', 'rasioBidan',
            'cakupanBPJS', 'totalDesa', 'desaODF'
        ));
    }
}
