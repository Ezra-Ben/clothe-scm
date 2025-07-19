<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class InboundShipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'procurement_request_id',
        'supplier_id',
        'carrier_id',
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'actual_delivery_date', 
    ];

    protected $casts = [
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function pod()
    {
        return $this->morphOne(Pod::class, 'shipment');
    }
}