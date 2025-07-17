<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'contact_phone',
        'vehicle_type',
        'license_plate',
        'service_areas', 
        'max_weight_kg',
        'customer_rating',
    ];

    public function inboundShipments()
    {
        return $this->hasMany(InboundShipment::class);
    }

    public function outboundShipments()
    {
        return $this->hasMany(OutboundShipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}