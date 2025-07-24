<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobTitle;

class JobTitlesSeeder extends Seeder
{
    public function run()
    {
        $jobTitles = [
            // Production roles
            'Sewing Machine Operator',
            'Pattern Cutter',
            'Quality Control Inspector',
            'Fabric Cutter',
            'Sample Maker',
            'Production Supervisor',
            'Embroidery Specialist',
            'Tailor',
            'Ironing & Finishing Technician',
            'Assembly Line Worker',

            // Inventory roles
            'Warehouse Clerk',
            'Inventory Analyst',

            // Distribution & Logistics roles
            'Logistics Coordinator',
            'Dispatch Officer',
        ];

        foreach ($jobTitles as $title) {
            JobTitle::create(['name' => $title]);
        }
    }
}
