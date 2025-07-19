<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductionService;

class TryPlannedProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example usage: php artisan production:try-planned
     */
    protected $signature = 'production:try-planned';

    /**
     * The console command description.
     */
    protected $description = 'Try to start any planned production orders if raw materials are now available';

    /**
     * Execute the console command.
     */
    public function handle(ProductionService $productionService)
    {
        $this->info('Checking for planned production orders...');

        $productionService->tryStartPlannedProduction();

        $this->info('Done.');
    }
}
