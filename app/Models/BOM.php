<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class BOM extends Model
{
    protected $fillable = ['finished_product_id', 'raw_material_id', 'quantity'];

    public function finishedProduct()
     { 
        return $this->belongsTo(Product::class, 'finished_product_id');
     }
    public function rawMaterial()
     { 
        return $this->belongsTo(Product::class, 'raw_material_id');
     }
}