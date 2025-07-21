<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'dob',
        'department_id',
        'job_title_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }
}
