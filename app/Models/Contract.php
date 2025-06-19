<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'supplier_id',
        'file_url',
        'uploaded_by',
        'status',
        'uploaded_at',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }//
}
