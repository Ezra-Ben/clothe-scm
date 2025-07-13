<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
