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
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function qualityControl()
    {
        return $this->hasOne(QualityControl::class);
    }

}
