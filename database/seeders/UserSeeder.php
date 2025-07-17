<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Carrier Alpha',
            'email' => 'carrier1@example.com',
            'password' => bcrypt('carrier1pass'), 
        ]);

        User::factory()->create([
            'name' => 'Carrier Beta',
            'email' => 'carrier2@example.com',
            'password' => bcrypt('carrier2pass'),
        ]);
    }
}
