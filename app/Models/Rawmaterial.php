<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class RawMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'unit', 'quantity_in_stock'];
    public function bomItems() { return $this->hasMany(BomItem::class); }
}

