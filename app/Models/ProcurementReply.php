<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementReply extends Model
{
    protected $fillable = [
        'procurement_request_id',
        'supplier_id',
        'quantity_confirmed',
        'expected_delivery_date',
        'status',
        'remarks',
    ];

    public function request()
    {
        return $this->belongsTo(ProcurementRequest::class, 'procurement_request_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
