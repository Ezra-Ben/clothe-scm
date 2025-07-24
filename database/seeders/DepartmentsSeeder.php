<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'Production & Manufacturing',
            'Inventory Management',
            'Distribution & Logistics',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
