<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
    $roles = [
        ['name' => 'admin', 'label' => 'Administrator'],
        ['name' => 'supplier', 'label' => 'Supplier'],
        ['name' => 'supplier_manager', 'label' => 'Supplier Manager'],
        ['name' => 'customer', 'label' => 'Customer'],
        ['name' => 'customer_manager', 'label' => 'Customer Manager'],
        ['name' => 'product_manager', 'label' => 'Product Manager'],
        ['name' => 'carrier', 'label' => 'Carrier'],
        ['name' => 'manager', 'label' => 'Manager'],
        ['name' => 'employee', 'label' => 'Employee'],
    ];

    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role['name']], $role);

    }
    }
}
