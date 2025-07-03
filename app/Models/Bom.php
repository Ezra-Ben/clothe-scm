<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Bom extends Model
{
    use HasFactory;
    protected $fillable = ['product_id'];
    public function product() { return $this->belongsTo(Product::class); }
    public function bomItems() { return $this->hasMany(BomItem::class); }
    public function productionOrders() { return $this->hasMany(ProductionOrder::class); }
}
