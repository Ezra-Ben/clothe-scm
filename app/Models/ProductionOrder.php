<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductionOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'batch_code', 'product_id', 'quantity', 'status',
        'urgent', 'packaging_status', 'scheduled_at', 'completed_at', 'bom_id'
    ];
    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'urgent' => 'boolean',
    ];
    public function product() { return $this->belongsTo(Product::class); }
    public function bom() { return $this->belongsTo(Bom::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); } // Items THIS production order fulfills
}
