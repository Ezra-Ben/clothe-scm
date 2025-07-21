<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Allocation;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;

        $allocations = Allocation::where('employee_id', $employee->id)
                                ->with('task')
                                ->latest('updated_at')
                                ->get();

        return view('employee.dashboard', compact('employee', 'allocations'));
    }

    public function show(Task $task)
    {
        $employee = auth()->user()->employee;

        // Confirm this task belongs to the employee
        $allocation = $task->allocations()
                           ->where('employee_id', $employee->id)
                           ->first();

        if (!$allocation) {
            abort(403, 'You are not assigned to this task.');
        }

        $coWorkers = $task->allocations()->with('employee.user')->get();

        return view('employee.task-show', compact('task', 'coWorkers', 'allocation'));
    }

    public function updateStatus(Request $request, Allocation $allocation)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Process,Complete',
        ]);

        if ($allocation->employee->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $allocation->status = $request->status;

        if ($request->status === 'Complete') {
            $allocation->completed_at = now();
        }

        $allocation->save();

        $task = $allocation->task;

        $incomplete = $task->allocations()
                           ->where('status', '!=', 'Complete')
                           ->count();

        if ($incomplete === 0) {
            $task->status = 'Complete';
            $task->save();
        }

        return redirect()->route('employee.dashboard')
                         ->with('success', 'Status updated successfully.');
    }
}
