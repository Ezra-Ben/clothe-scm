<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Employee;
use App\Models\Task;
use App\Models\Allocation;

class EmployeeDashboardController extends Controller
{
  public function index()
{
    $employee = Employee::where('user_id', auth()->id())->firstOrFail();
    
    // This ensures fresh data with proper ordering
    $allocations = Allocation::where('employee_id', $employee->id)
                           ->with('task')
                           ->orderBy('updated_at', 'desc')
                           ->get();

    return view('employee.dashboard', compact('employee', 'allocations'));
}

public function show(Task $task)
{
    $employee = Employee::where('user_id', auth()->id())->firstOrFail();
    
    // All coworkers on the task
    $coWorkers = $task->allocations()->with('employee')->get();

    // Find the current user's allocation
    $allocation = $task->allocations()
                      ->where('employee_id', $employee->id)
                      ->first();

    return view('employee.task-show', compact('task', 'coWorkers', 'allocation'));
}


public function updateStatus(Request $request, Allocation $allocation)
{
    $validated = $request->validate([
        'status' => 'required|in:Pending,In Process,Complete'
    ]);

    // Step 3: Update the allocation status
    $allocation->status = $validated['status'];
    $allocation->save();

    // Step 4: Check if ALL allocations for the task are marked as 'Complete'
    $task = $allocation->task;

    $incompleteAllocations = $task->allocations()
                                  ->where('status', '!=', 'Complete')
                                  ->count();

    // Step 5: If all allocations are complete, mark task as complete
    if ($incompleteAllocations === 0) {
        $task->status = 'Complete';
        $task->save();
    }

    return redirect()->route('employee.dashboard')
                     ->with('success', 'Status updated successfully.');
}

}
