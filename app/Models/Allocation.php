<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $fillable = ['employee_id', 'task_id', 'scheduled_at', 'duration_minutes', 'status'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function task() {
        return $this->belongsTo(Task::class);
    }
}

