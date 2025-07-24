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
        'department_id',
    ];

    public function allowedJobTitles()
    {
        return $this->belongsToMany(JobTitle::class, 'job_title_task')
                    ->withPivot('required_count');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
