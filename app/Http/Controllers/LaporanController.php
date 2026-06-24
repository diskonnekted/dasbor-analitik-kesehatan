<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    private function getDataByType($type)
    {
        $data = [];
        $title = '';
        $headers = [];

        switch ($type) {
            case 'stunting':
                $title = 'Laporan Stunting Tahunan';
                $tahun = \App\Models\Stunting::max('tahun') ?? 2025;
                $data = \App\Models\Stunting::where('tahun', $tahun)
                    ->join('kecamatans', 'stuntings.kecamatan_id', '=', 'kecamatans.id')
                    ->select('kecamatans.nama as Kecamatan', 'stuntings.jumlah_balita as Total Balita', 'stuntings.jumlah_balita_diukur as Balita Diukur', 'stuntings.jumlah_stunting as Jumlah Stunting', \DB::raw('ROUND((stuntings.jumlah_stunting * 100.0 / NULLIF(stuntings.jumlah_balita_diukur, 0)), 2) as "Prevalensi (%)"'))
                    ->get()->toArray();
                $headers = ['Kecamatan', 'Total Balita', 'Balita Diukur', 'Jumlah Stunting', 'Prevalensi (%)'];
                break;
            case 'penyakit':
                $title = "Laporan Wabah & Penyakit";
                $tahun = \App\Models\KasusPenyakit::max('tahun') ?? 2025;
                $data = \App\Models\KasusPenyakit::where('tahun', $tahun)
                    ->join('kecamatans', 'kasus_penyakits.kecamatan_id', '=', 'kecamatans.id')
                    ->select(
                        'kecamatans.nama as Kecamatan', 
                        'kasus_penyakits.malaria as Malaria', 
                        'kasus_penyakits.tb_paru as TB Paru', 
                        'kasus_penyakits.pneumonia as Pneumonia', 
                        'kasus_penyakits.kusta as Kusta',
                        'kasus_penyakits.tetanus_neonatorum as Tetanus',
                        'kasus_penyakits.campak as Campak',
                        'kasus_penyakits.diare as Diare', 
                        'kasus_penyakits.dbd as DBD',
                        'kasus_penyakits.hiv_baru as HIV',
                        'kasus_penyakits.ims as IMS'
                    )
                    ->get()->toArray();
                $headers = ['Kecamatan', 'Malaria', 'TB Paru', 'Pneumonia', 'Kusta', 'Tetanus', 'Campak', 'Diare', 'DBD', 'HIV', 'IMS'];
                break;
            case 'faskes':
                $title = "Laporan Fasilitas Kesehatan Tahunan";
                $tahun = \App\Models\Faskes::max('tahun') ?? 2025;
                $data = \App\Models\Faskes::where('tahun', $tahun)
                    ->join('kecamatans', 'faskes.kecamatan_id', '=', 'kecamatans.id')
                    ->select('kecamatans.nama as Kecamatan', 'faskes.rs_umum as RS Umum', 'faskes.puskesmas as Puskesmas', 'faskes.klinik as Klinik', 'faskes.posyandu as Posyandu', 'faskes.poskesdes as Poskesdes', 'faskes.total as Total')
                    ->get()->toArray();
                $headers = ['Kecamatan', 'RS Umum', 'Puskesmas', 'Klinik', 'Posyandu', 'Poskesdes', 'Total'];
                break;
            case 'nakes':
                $title = 'Laporan Tenaga Kesehatan';
                $tahun = \App\Models\TenagaKesehatan::max('tahun') ?? 2025;
                $data = \App\Models\TenagaKesehatan::where('tahun', $tahun)
                    ->join('kecamatans', 'tenaga_kesehatans.kecamatan_id', '=', 'kecamatans.id')
                    ->select(
                        'kecamatans.nama as Kecamatan', 
                        'tenaga_kesehatans.dokter_umum as Dokter Umum', 
                        'tenaga_kesehatans.dokter_gigi as Dokter Gigi', 
                        'tenaga_kesehatans.perawat as Perawat', 
                        'tenaga_kesehatans.bidan as Bidan',
                        'tenaga_kesehatans.farmasi as Farmasi',
                        'tenaga_kesehatans.gizi as Gizi',
                        'tenaga_kesehatans.total as Total Nakes'
                    )
                    ->get()->toArray();
                $headers = ['Kecamatan', 'Dokter Umum', 'Dokter Gigi', 'Perawat', 'Bidan', 'Farmasi', 'Gizi', 'Total Nakes'];
                break;
            default:
                abort(404, 'Tipe laporan tidak ditemukan');
        }

        return compact('data', 'title', 'headers');
    }

    public function generate(Request $request)
    {
        $type = $request->query('type', 'stunting');
        $report = $this->getDataByType($type);
        
        return view('laporan.show', $report);
    }

    public function exportExcel(Request $request)
    {
        $type = $request->query('type', 'stunting');
        $report = $this->getDataByType($type);
        
        $filename = "Export_{$type}_" . date('Ymd') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = $report['headers'];
        $data = $report['data'];

        $callback = function() use($columns, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($data as $row) {
                fputcsv($file, array_values($row));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF(Request $request)
    {
        $type = $request->query('type', 'stunting');
        $report = $this->getDataByType($type);
        
        return view('laporan.print', $report);
    }
}
