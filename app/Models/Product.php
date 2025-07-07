<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'category_id', 'sku', 'status'
    ];

    public function productionBatches()
    {
        return $this->hasMany(\App\Models\ProductionBatch::class);
    }

    public function qualityControls()
    {
        return $this->hasManyThrough(
            \App\Models\QualityControl::class,
            \App\Models\ProductionBatch::class,
            'product_id', // Foreign key on ProductionBatch table
            'production_batch_id', // Foreign key on QualityControl table
            'id', // Local key on Product table
            'id'  // Local key on ProductionBatch table
        );
    }
}
