<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'requested_by',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
}
