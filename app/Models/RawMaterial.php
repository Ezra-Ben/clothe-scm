<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'unit_of_measure',
        'quantity_on_hand',
        'reorder_point',
        'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function bomItems()
    {
        return $this->hasMany(BomItem::class);
    }

}
