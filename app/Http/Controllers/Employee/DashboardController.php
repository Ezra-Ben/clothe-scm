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

        return view('task.show', compact('task', 'coWorkers', 'allocation'));
    }

    public function updateStatus(Request $request, Allocation $allocation)
    {
        $request->validate([
            'status' => 'required|in:pending,in_process,complete',
        ]);

        if ($allocation->employee->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $allocation->status = $request->status;

        if ($request->status === 'complete') {
            $allocation->completed_at = now();
        }

        $allocation->save();

        $task = $allocation->task;

        $incomplete = $task->allocations()
                           ->where('status', '!=', 'complete')
                           ->count();

        if ($incomplete === 0) {
            $task->status = 'complete';
            $task->save();
        }

        // Check if this employee still has any incomplete allocations
        $remaining = Allocation::where('employee_id', $employee->id)
                                ->where('status', '!=', 'complete')
                                ->exists();

        if (!$remaining) {
            $employee->status = 'unassigned';
            $employee->save();
        }

        return redirect()->route('employee.dashboard')
                         ->with('success', 'Status updated successfully.');
    }
}
