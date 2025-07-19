<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Services\NotificationService;
use App\Http\Requests\StoreAllocationRequest;


class WorkforceController extends Controller
{
    public function dashboard()
    {
        $unassignedTasks = Task::where('status', 'Unassigned')->get();
        $assignedTasks = Task::whereIn('status', ['Assigned', 'Complete'])->get();


        return view('workforce.dashboard', compact('unassignedTasks', 'assignedTasks'));
    }

    public function assignView(Task $task)
    {
        $employees = Employee::whereIn('position_id', $task->allowedPositions->pluck('id'))->get();
        return view('workforce.assign', compact('task', 'employees'));
    }

    public function assign(StoreAllocationRequest $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'task_id' => 'required|exists:tasks,id',
    ]);

    $employee = Employee::with('position')->findOrFail($request->employee_id);
    $task = Task::with('positionRequirements')->findOrFail($request->task_id);

    // Avoid duplicate assignment
    $alreadyAssigned = Allocation::where('employee_id', $employee->id)
                                  ->where('task_id', $task->id)
                                  ->exists();

    if ($alreadyAssigned) {
        return redirect()->back()->with('error', 'This employee is already assigned to this task.');
    }

    // Assign the employee
    Allocation::create([
        'employee_id' => $employee->id,
        'task_id' => $task->id,
        'scheduled_at' => now(),
        'status' => 'Pending',
        'duration_minutes' => $task->average_duration_minutes,
    ]);

    // Send notification to the assigned user
NotificationService::notifyUser(
    $employee->user->id, // assuming there's a 'user' relationship on Employee
    'Task Assignment',
    'You have been assigned to the task: ' . $task->name,
    route('employee.task.show', $task->id) // make sure this route exists
);


    // Step 1: Get current assigned count per position
    $currentAssignments = $task->allocations()
        ->with('employee.position')
        ->get()
        ->groupBy(fn($allocation) => $allocation->employee->position->id)
        ->map(fn($group) => $group->count());

    // Step 2: Check if all required positions are filled
    $fullyAssigned = true;

    foreach ($task->positionRequirements as $position) {
        $required = $position->pivot->required_count;
        $assigned = $currentAssignments[$position->id] ?? 0;

        if ($assigned < $required) {
            $fullyAssigned = false;
            break;
        }
    }

    // Step 3: Redirect appropriately
    if ($fullyAssigned) {
        $task->status = 'Assigned';
$task->save();
        return redirect()->route('workforce.dashboard')->with('success', 'Task fully assigned.');
    } else {
        return redirect()
            ->route('workforce.assign.view', $task->id)
            ->with('info', "{$employee->name} assigned. Task still needs more workers.");
    }


}

    public function showEmployee(Employee $employee)
    {
        $allocations = $employee->allocations()->with('task')->get();
        return view('employees.show', compact('employee', 'allocations'));
    }
}
