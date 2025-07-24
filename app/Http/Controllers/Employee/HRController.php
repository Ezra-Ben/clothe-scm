<?php

namespace App\Http\Controllers\Employee;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HRController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalJobTitles = JobTitle::count();

        $departments = Department::withCount('employees')->get();

        // Calculate department employee percentages
        foreach ($departments as $dept) {
            $dept->percentage = $totalEmployees > 0
                ? ($dept->employees_count / $totalEmployees) * 100
                : 0;
        }

        return view('hr.dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'totalJobTitles',
            'departments'
        ));
    }
}
