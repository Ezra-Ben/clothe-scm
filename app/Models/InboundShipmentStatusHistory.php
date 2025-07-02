<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundShipmentStatusHistory extends Model
{
 protected $fillable = ['inbound_shipment_id', 'status', 'changed_at', 'changed_by'];
 protected $casts = ['changed_at' => 'datetime',];

    public function shipment()
    {
        return $this->belongsTo(InboundShipment::class, 'inbound_shipment_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
