<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = ['batch_code', 'product_id', 'status', 'scheduled_at',
        'is_urgent', 'packaging_status', 'order_id'
];

 public function product()
    {
        return $this->belongsTo(Product::class,'productId');
    }
public function order()
    {
        return $this->belongsTo(Order::class);
    }
public function productionRequest()
    {
        return $this->belongsTo(productionRequest::class);
    }
}
