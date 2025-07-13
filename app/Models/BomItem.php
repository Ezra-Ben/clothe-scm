<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    protected $fillable = [
        'bom_id',
        'raw_material_id',
        'quantity',
        'unit_of_measure',
    ];

    public function bom()
    {
        return $this->belongsTo(Bom::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
