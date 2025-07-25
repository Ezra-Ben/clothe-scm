<?php

namespace App\Http\Controllers\WorkForce;

use App\Models\JobTitle;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create()
    {
        $jobTitles = JobTitle::all();
        $departmentId = request('department_id');
        return view('task.create', compact('jobTitles', 'departmentId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'average_duration_minutes' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
            'allowed_job_titles' => 'required|array|min:1',
            'allowed_job_titles.*.job_title_id' => 'required|exists:job_titles,id',
            'allowed_job_titles.*.required_count' => 'required|integer|min:1',
        ]);

        $this->taskService->createTask($data);

        return redirect()->route('workforce.dashboard', ['department_id' => $data['department_id']])->with('success', 'Task created successfully.');
    }
}
