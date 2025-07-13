<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'subtotal',
        'total',
        'status',
        'payment_method',
        'tax',
        'shipping',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function fulfillment() {
        return $this->hasOne(OrderFulfillment::class);
    }
}
