<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = [
        'production_order_id',
        'produced_quantity',
        'started_at',
        'completed_at',
        'status',
        'schedule_id',
    ];

    protected $casts = [
    'started_at' => 'datetime',
    'completed_at' => 'datetime',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function qualityControl()
    {
        return $this->hasOne(QualityControl::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function resourceAssignments()
    {
        return $this->hasMany(ResourceAssignment::class, 'batch_id');
    }


}
