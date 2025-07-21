<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'scheduled_date',
        'average_duration_minutes',
        'status',
    ];

    // Job titles allowed to handle this task
    public function allowedJobTitles()
    {
        return $this->belongsToMany(JobTitle::class, 'job_title_task');
    }

    // Allocated employees
    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }
}
