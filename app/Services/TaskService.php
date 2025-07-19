<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\DB;  

class TaskService
{
   public function createTask(array $data)
{
    return DB::transaction(function () use ($data) {
        // Create the task
        $task = Task::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'scheduled_date' => $data['scheduled_date'],
            'average_duration_minutes' => $data['average_duration_minutes'],
            'status' => 'Unassigned',
        ]);

        // Attach positions and required counts
        foreach ($data['positions'] as $position) {
            $task->positions()->attach($position['position_id'], [
                'required_count' => $position['required_count']
            ]);
        }

        return $task;
    });
}
}
