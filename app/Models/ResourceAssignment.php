<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAssignment extends Model
{
    use HasFactory;
    protected $table='resources_assignment' ; 
    protected $fillable = [
        'resource_id',
        'batch_id',
        'assignable_type',
        'assignable_id',
        'purpose',
        'assigned_start_time',
        'assigned_end_time',
        'expected_duration_minutes',
        'status',
    ];

    protected $casts = [
        'assigned_start_time' => 'datetime',
        'assigned_end_time' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    // If using polymorphic relations for assignable_type/id
    public function assignable()
    {
        return $this->morphTo();
    }
}