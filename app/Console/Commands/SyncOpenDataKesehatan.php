<?php
// app/Console/Commands/SyncOpenDataKesehatan.php

namespace App\Console\Commands;

use App\Services\OpenDataBanjarnegaraService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOpenDataKesehatan extends Command
{
    protected $signature = 'opendata:sync-kesehatan {--dataset= : Dataset spesifik yang ingin di-sync}';
    protected $description = 'Sync data kesehatan dari OpenData Banjarnegara';
    
    public function handle()
    {
        $this->info('?? Starting sync dari OpenData Banjarnegara...');
        
        $service = new OpenDataBanjarnegaraService();
        $datasetFilter = $this->option('dataset');
        
        try {
            if ($datasetFilter) {
                $this->info("?? Syncing dataset: {$datasetFilter}");
                $csvContent = $service->downloadDataset($datasetFilter);
                $data = $service->parseCSV($csvContent);
                
                // Gunakan reflection untuk call private method
                $reflection = new \ReflectionClass($service);
                $method = $reflection->getMethod('importDataset');
                $method->setAccessible(true);
                $imported = $method->invoke($service, $datasetFilter, $data);
                
                $this->info("? Berhasil import {$imported} record untuk {$datasetFilter}");
            } else {
                $this->info("?? Syncing semua dataset kesehatan...");
                $results = $service->importAll();
                
                foreach ($results as $key => $result) {
                    if ($result['success']) {
                        $this->info("? {$key}: {$result['message']}");
                    } else {
                        $this->error("? {$key}: {$result['message']}");
                    }
                }
            }
            
            $this->info('?? Sync completed!');
            
        } catch (\Exception $e) {
            $this->error('? Error: ' . $e->getMessage());
            Log::error('Sync error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
