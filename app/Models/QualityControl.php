<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    protected $fillable = [
        'production_batch_id', 'tester_id', 'defects_found', 'status', 'tested_at', 'notes'
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }
}
