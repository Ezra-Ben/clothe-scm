<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            [
                'name' => 'Juki DDL-8700 Sewing Machine',
                'type' => 'machine',
                'description' => 'High-speed single needle lockstitch machine',
                'capacity_units_per_hour' => 100,
                'status' => 'available',
            ],
            [
                'name' => 'Brother Serger 1034D',
                'type' => 'machine',
                'description' => 'Overlock sewing machine for edge finishing',
                'capacity_units_per_hour' => 80,
                'status' => 'available',
            ],
            [
                'name' => 'Industrial Cutting Table',
                'type' => 'equipment',
                'description' => 'Used for manual fabric cutting and layout',
                'capacity_units_per_hour' => 50,
                'status' => 'available',
            ],
            [
                'name' => 'Steam Press Iron',
                'type' => 'tool',
                'description' => 'Used for finishing and pressing garments',
                'capacity_units_per_hour' => 70,
                'status' => 'maintenance',
            ],
            [
                'name' => 'Embroidery Machine',
                'type' => 'machine',
                'description' => 'Automated embroidery unit for logos and branding',
                'capacity_units_per_hour' => 40,
                'status' => 'available',
            ],
            [
                'name' => 'Thread Overlock Station',
                'type' => 'station',
                'description' => 'Workstation for thread overlocking operations',
                'capacity_units_per_hour' => 65,
                'status' => 'in_use',
            ],
        ];

        foreach ($resources as $data) {
            Resource::create($data);
        }
    }
}
