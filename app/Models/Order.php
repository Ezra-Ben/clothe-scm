<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'status'];

    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
        public function orderItems() { 
            return $this->hasMany(OrderItem::class);
         }

}
