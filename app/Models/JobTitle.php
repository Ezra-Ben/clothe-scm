<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $fillable = ['name'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'job_title_task');
    }

}
