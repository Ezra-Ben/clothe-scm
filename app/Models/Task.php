<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'description', 'scheduled_date', 'average_duration_minutes', 'status'];

    public function allowedPositions() {
        return $this->belongsToMany(Position::class, 'position_task');
    }

    public function allocations() {
        return $this->hasMany(Allocation::class);
    }

    public function positionRequirements()
{
    return $this->belongsToMany(Position::class, 'position_task')
                ->withPivot('required_count')
                ->withTimestamps();
}

public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_task')
                    ->withPivot('required_count')
                    ->withTimestamps();
    }

}
