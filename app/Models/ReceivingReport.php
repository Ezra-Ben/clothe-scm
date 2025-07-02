<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'received_by',
        'received_at',
        'condition',
        'discrepancy_notes'
    ];

    protected $casts = [
        'received_at' => 'datetime'
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(InboundShipment::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}