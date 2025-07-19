<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Position;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create()
    {
        $positions = Position::all();
        return view('tasks.create', compact('positions'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());

       
        return redirect()->route('workforce.dashboard')->with('success', 'Task created successfully.');
    }
}
