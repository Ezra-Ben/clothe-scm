<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InboundShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_order_id',
        'carrier_id',
        'tracking_number',
        'status',
        'estimated_arrival'
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime','received_items' => 'array'
    ];

    public function supplierOrder(): BelongsTo
    {
        return $this->belongsTo(SupplierOrder::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }
    public function statusHistories()
{
    return $this->hasMany(InboundShipmentStatusHistory::class);
}


   

    // Helper method for status display
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-secondary',
            'in_transit' => 'bg-primary',
            'arrived' => 'bg-warning',
            'received' => 'bg-success',
            default => 'bg-light'
        };
    }
    public function supplier()
{
    return $this->hasOneThrough(
        Supplier::class,
        SupplierOrder::class,
        'id', // Foreign key on SupplierOrder table...
        'id', // Foreign key on Supplier table...
        'supplier_order_id', // Local key on InboundShipment table...
        'supplier_id' // Local key on SupplierOrder table...
    );
}
public function receivingReport()
{
    return $this->hasOne(ReceivingReport::class, 'shipment_id');
}

}