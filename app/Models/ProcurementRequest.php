<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    protected $fillable = [
        'raw_material_id',
        'quantity',
        'status',
        'order_id',
        'production_order_id',
        'supplier_id',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function replies()
    {
        return $this->hasMany(ProcurementReply::class);
    }

    public function supplier()
    {
    return $this->belongsTo(Supplier::class);
    }

}
