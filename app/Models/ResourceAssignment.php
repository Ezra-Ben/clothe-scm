<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAssignment extends Model
{
    use HasFactory; 

    protected $fillable = [
        'resource_id',
        'batch_id',
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
        return $this->belongsTo(ProductionBatch::class, 'batch_id');
    }
}
