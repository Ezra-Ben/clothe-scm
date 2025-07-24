<?php

namespace App\Http\Controllers\WorkForce;

use App\Models\Task;
use App\Models\Employee;
use App\Models\Allocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\TaskAssignedNotification;

class WorkforceController extends Controller
{
    public function dashboard(Request $request)
    {
        $departmentId = $request->input('department_id');

        $unassignedTasks = Task::where('status', 'pending')
            ->where('department_id', $departmentId)
            ->get();

        $assignedTasks = Task::whereIn('status', ['assigned', 'complete'])
            ->where('department_id', $departmentId)
            ->get();
        return view('workforce.dashboard', compact('unassignedTasks', 'assignedTasks', 'departmentId'));
    }

    public function assignView(Request $request, Task $task)
    {
        $departmentId = $request->input('department_id');

        $employees = Employee::where('department_id', $departmentId)
            ->whereIn('job_title_id', $task->allowedJobTitles->pluck('id'))
            ->get();

        return view('workforce.assign', compact('task', 'employees', 'departmentId'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $employee = Employee::with('user')->findOrFail($request->employee_id);
        $task = Task::with(['allowedJobTitles', 'allocations.employee.jobTitle'])->findOrFail($request->task_id);

        // Prevent duplicates
        $alreadyAssigned = Allocation::where([
            ['employee_id', $employee->id],
            ['task_id', $task->id],
        ])->exists();

        if ($alreadyAssigned) {
            return back()->with('error', 'This employee is already assigned to this task.');
        }

        Allocation::create([
            'employee_id' => $employee->id,
            'task_id' => $task->id,
            'assigned_at' => now(),
            'status' => 'assigned',
        ]);

        $employee->status = 'assigned';
        $employee->save();

        $employee->user->notify(new TaskAssignedNotification($task));

        // Group current assignments by job_title_id
        $currentAssignments = $task->allocations
            ->groupBy(fn ($allocation) => $allocation->employee->job_title_id)
            ->map->count();

        $fullyAssigned = $task->allowedJobTitles->every(function ($jt) use ($currentAssignments) {
            return ($currentAssignments[$jt->id] ?? 0) >= $jt->pivot->required_count;
        });

        if ($fullyAssigned) {
            $task->update(['status' => 'assigned']);
            return redirect()->route('workforce.dashboard', ['department_id' => $task->department_id])->with('success', 'Task fully assigned.');
        }

        return redirect()
            ->route('workforce.assign.view', ['task' => $task->id, 'department_id' => $task->department_id])
            ->with('info', "{$employee->user->name} assigned. More employees still needed.");
    }

    public function showEmployee(Employee $employee)
    {
        $allocations = $employee->allocations()->with('task')->get();
        return view('employees.show', compact('employee', 'allocations'));
    }
}
