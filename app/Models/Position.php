<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['name'];

    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function tasks()
{
    return $this->belongsToMany(Task::class, 'position_task')
                ->withPivot('required_count')
                ->withTimestamps();
}

}

