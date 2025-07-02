<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
         'code',
         'contact_phone',
         'supported_service_levels',
         'service_areas',
         'base_rate_usd',
         'max_weight_kg',
        'tracking_url_template',
        
    ];

    protected $casts = [
         'supported_service_levels' => 'array',
         'service_areas' => 'array'
    ];

    
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
    public function scopeActive($query)
{
    return $query->where('is_active', true);
}
}
