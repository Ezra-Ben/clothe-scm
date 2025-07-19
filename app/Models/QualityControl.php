<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    protected $fillable = [
        'production_batch_id',
        'status', 
        'inspection_date',
        'defect_count',
        'notes',
        'corrective_action_taken',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }
}
