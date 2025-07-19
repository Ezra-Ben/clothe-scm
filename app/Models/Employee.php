<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'dob', 'phone', 'position_id','user_id'];

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function allocations() {
        return $this->hasMany(Allocation::class);
    }

    public function assignedTasks() {
        return $this->belongsToMany(Task::class, 'allocations');
    }

public function user()
{
    return $this->belongsTo(User::class);
}

}

