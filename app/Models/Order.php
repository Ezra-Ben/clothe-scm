<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id', 'quantity', 'status', 'order_date', 'production_start_date'];
    protected $dates = ['order_date', 'production_start_date'];
    protected $casts = ['production_start_date' => 'datetime','order_date'=> 'datetime',
     'created_at'=> 'datetime'
      ,'updated_at'=> 'datetime'
];

    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
        public function orderItems() { 
            return $this->hasMany(OrderItem::class);
         }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
     public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['start_date'] ?? false, function ($query, $date) {
            $query->whereDate('start_date', '>=', $date);
        });

        $query->when($filters['end_date'] ?? false, function ($query, $date) {
            $query->whereDate('end_date', '<=', $date);
        });

}
}
    
