<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'product_id',
        'quantity',
        'status',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function qualityControls()
    {
        return $this->hasMany(QualityControl::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
