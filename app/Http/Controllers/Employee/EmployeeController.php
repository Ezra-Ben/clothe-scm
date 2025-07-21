<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user', 'department', 'jobTitle')->get();
        return view('employees.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::with('user', 'department', 'jobTitle')->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::with('user', 'department', 'jobTitle')->findOrFail($id);
        $departments = Department::all();
        $jobTitles = JobTitle::all();

        $canEditHR = auth()->user()->hasRole('hr_manager');
        $canEditEmployee = auth()->user()->id === $employee->user_id && auth()->user()->hasRole('employee');

        if (!($canEditHR || $canEditEmployee)) {
            abort(403);
        }

        return view('employees.edit', compact('employee', 'departments', 'jobTitles', 'canEditHR', 'canEditEmployee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $user = auth()->user();

        $isHR = $user->hasRole('hr_manager');
        $isEmployee = $user->id === $employee->user_id && $user->hasRole('employee');

        if (!($isHR || $isEmployee)) {
            abort(403);
        }

        if ($isEmployee) {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $employee->user_id,
                'contact' => 'nullable|string|max:255',
            ]);

            $employee->user->update([
                'email' => $validated['email'],
                'contact' => $validated['contact'],
            ]);
        }

        if ($isHR) {
            $validated = $request->validate([
                'department_id' => 'required|exists:departments,id',
                'job_title_id' => 'required|exists:job_titles,id',
            ]);

            $employee->update([
                'department_id' => $validated['department_id'],
                'job_title_id' => $validated['job_title_id'],
            ]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Profile updated.');
    }


    public function create()
    {
        $departments = Department::all();
        $jobTitles = JobTitle::all();
        return view('employees.create', compact('departments', 'jobTitles'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'dob' => 'nullable|date',
            'department_id' => 'required|exists:departments,id',
            'job_title_id' => 'required|exists:job_titles,id',
        ]);

        // Temporarily store user+employee data in session (except password)
        Session::put('employee_registration_data', $validated);

        // Redirect to password creation form
        return redirect()->route('employees.set_password');
    }

    public function setPassword()
    {
        if (!Session::has('employee_registration_data')) {
            return redirect()->route('employees.create')->with('error', 'Session expired. Please start again.');
        }

        return view('employees.set_password');
    }

    public function finalizeRegistration(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = Session::get('employee_registration_data');

        if (!$data) {
            return redirect()->route('employees.create')->with('error', 'Session expired. Please start again.');
        }

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($request->password),
            'role_id' => 10, 
        ]);

        // Create employee
        Employee::create([
            'user_id' => $user->id,
            'dob' => $data['dob'],
            'department_id' => $data['department_id'],
            'job_title_id' => $data['job_title_id'],
            'status' => 'unassigned',
        ]);

        // Clear session
        Session::forget('employee_registration_data');

        return redirect()->route('employees.create')->with('success', 'Employee registered successfully.');
}

}
