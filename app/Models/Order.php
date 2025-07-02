<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['product_id', 'customer_id', 'quantity', 'status'];
    public function product() { return $this->belongsTo(Product::class); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
}