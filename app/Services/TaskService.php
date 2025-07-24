<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function createTask(array $data)
    {
        return DB::transaction(function () use ($data) {
            $task = Task::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'scheduled_date' => $data['scheduled_date'],
                'average_duration_minutes' => $data['average_duration_minutes'],
                'status' => 'pending',
                'department_id' => $data['department_id'],
            ]);

            foreach ($data['allowed_job_titles'] as $job) {
                $task->allowedJobTitles()->attach(
                    $job['job_title_id'],
                    ['required_count' => $job['required_count']]
                );
            }

            return $task;
        });
    }
}
