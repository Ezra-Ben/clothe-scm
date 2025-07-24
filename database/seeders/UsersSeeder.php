<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Alice Tailor', 'email' => 'alice@scm.test'],
            ['name' => 'Bob Cutter', 'email' => 'bob@scm.test'],
            ['name' => 'Charlie Sew', 'email' => 'charlie@scm.test'],
            ['name' => 'Diana QC', 'email' => 'diana@scm.test'],
            ['name' => 'Ethan Sample', 'email' => 'ethan@scm.test'],
            ['name' => 'Fiona Supervisor', 'email' => 'fiona@scm.test'],
            ['name' => 'George Embroidery', 'email' => 'george@scm.test'],
            ['name' => 'Hannah Iron', 'email' => 'hannah@scm.test'],
            ['name' => 'Ian Assembly', 'email' => 'ian@scm.test'],
            ['name' => 'Jack QC', 'email' => 'jack@scm.test'],

            ['name' => 'Nancy Inventory', 'email' => 'nancy@scm.test'],
            ['name' => 'Oscar Warehouse', 'email' => 'oscar@scm.test'],

            ['name' => 'Paula Logistics', 'email' => 'paula@scm.test'],
            ['name' => 'Quincy Dispatch', 'email' => 'quincy@scm.test'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt('password'), 
                'role_id' => 10,
            ]);
        }
    }
}
