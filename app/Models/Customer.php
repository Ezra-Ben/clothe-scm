<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email',
        'phone',
        'shipping_address',
        'billing_address'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasManyThrough(Delivery::class, Order::class);
    }
}
