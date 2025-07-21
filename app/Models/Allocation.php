<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $fillable = [
        'employee_id',
        'task_id',
        'assigned_at',
        'completed_at',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
