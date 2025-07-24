<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\JobTitle;

class EmployeesSeeder extends Seeder
{
    public function run()
    {
        $productionDept = Department::where('name', 'Production & Manufacturing')->first();
        $inventoryDept = Department::where('name', 'Inventory Management')->first();
        $logisticsDept = Department::where('name', 'Distribution & Logistics')->first();

        $mapping = [
            ['email' => 'alice@scm.test', 'job' => 'Tailor', 'dept' => $productionDept],
            ['email' => 'bob@scm.test', 'job' => 'Pattern Cutter', 'dept' => $productionDept],
            ['email' => 'charlie@scm.test', 'job' => 'Sewing Machine Operator', 'dept' => $productionDept],
            ['email' => 'diana@scm.test', 'job' => 'Quality Control Inspector', 'dept' => $productionDept],
            ['email' => 'ethan@scm.test', 'job' => 'Sample Maker', 'dept' => $productionDept],
            ['email' => 'fiona@scm.test', 'job' => 'Production Supervisor', 'dept' => $productionDept],
            ['email' => 'george@scm.test', 'job' => 'Embroidery Specialist', 'dept' => $productionDept],
            ['email' => 'hannah@scm.test', 'job' => 'Ironing & Finishing Technician', 'dept' => $productionDept],
            ['email' => 'ian@scm.test', 'job' => 'Assembly Line Worker', 'dept' => $productionDept],
            ['email' => 'jack@scm.test', 'job' => 'Quality Control Inspector', 'dept' => $productionDept],

            ['email' => 'nancy@scm.test', 'job' => 'Inventory Analyst', 'dept' => $inventoryDept],
            ['email' => 'oscar@scm.test', 'job' => 'Warehouse Clerk', 'dept' => $inventoryDept],

            ['email' => 'paula@scm.test', 'job' => 'Logistics Coordinator', 'dept' => $logisticsDept],
            ['email' => 'quincy@scm.test', 'job' => 'Dispatch Officer', 'dept' => $logisticsDept],
        ];

        foreach ($mapping as $map) {
            $user = User::where('email', $map['email'])->first();
            $job = JobTitle::where('name', $map['job'])->first();

            if ($user && $job && $map['dept']) {
                Employee::create([
                    'user_id' => $user->id,
                    'dob' => now()->subYears(25)->format('Y-m-d'),
                    'department_id' => $map['dept']->id,
                    'job_title_id' => $job->id,
                    'status' => 'unassigned'
                ]);
            }
        }
    }
}
