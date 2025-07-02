<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BomItem extends Model
{
    use HasFactory;
    protected $fillable = ['bom_id', 'raw_material_id', 'quantity'];
    public function bom() { return $this->belongsTo(Bom::class); }
    public function rawMaterial() { return $this->belongsTo(RawMaterial::class); }
}

