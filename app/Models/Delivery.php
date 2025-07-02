<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'carrier_id',
        'tracking_number',
        'status',
        'estimated_delivery',
        'actual_delivery',
        'route',
        'notes',
        'service_level'
    ];

    protected $casts = [
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'route' => 'array'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

     public function statusHistories()
{
    return $this->hasMany(DeliveryStatusHistory::class);
}

    
    public function isDelayed(): bool
    {
        return $this->actual_delivery > $this->estimated_delivery;
    }

    public function trackingUrl(): ?string
    {
        if (!$this->carrier || !$this->tracking_number) {
            return null;
        }

        return str_replace(
            '{tracking_number}',
            $this->tracking_number,
            $this->carrier->tracking_url_template
        );
    } 
    public function getStatusColorAttribute(): string
{
    return match($this->status) {
        'processing' => 'secondary',
        'dispatched' => 'primary',
        'in_transit' => 'info',
        'out_for_delivery' => 'warning',
        'delivered' => 'success',
        'failed' => 'danger',
        default => 'light'
    };
}

}
