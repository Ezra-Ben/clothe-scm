<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sku', 'description'];
    public function boms() { return $this->hasMany(Bom::class); }
    public function finishedGoodsInventories() { return $this->hasMany(FinishedGoodsInventory::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function productionOrders() { return $this->hasMany(ProductionOrder::class); }
}

