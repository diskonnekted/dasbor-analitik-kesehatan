<?php
// app/Http/Controllers/Admin/OpenDataSyncController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OpenDataBanjarnegaraService;
use Illuminate\Http\Request;

class OpenDataSyncController extends Controller
{
    public function index()
    {
        return view('admin.opendata.sync');
    }
    
    public function sync(Request $request)
    {
        $request->validate([
            'dataset' => 'nullable|string|in:tenaga_kesehatan,faskes,kasus_penyakit,kematian_ibu_bayi,persalinan,pasien_rawat'
        ]);
        
        $service = new OpenDataBanjarnegaraService();
        
        try {
            if ($request->dataset) {
                $csvContent = $service->downloadDataset($request->dataset);
                $data = $service->parseCSV($csvContent);
                
                $reflection = new \ReflectionClass($service);
                $method = $reflection->getMethod('importDataset');
                $method->setAccessible(true);
                $imported = $method->invoke($service, $request->dataset, $data);
                
                return back()->with('success', "Berhasil sync {$imported} record untuk dataset {$request->dataset}");
            } else {
                $results = $service->importAll();
                $successCount = collect($results)->where('success', true)->count();
                
                return back()->with('success', "Berhasil sync {$successCount} dari " . count($results) . " dataset");
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
```

