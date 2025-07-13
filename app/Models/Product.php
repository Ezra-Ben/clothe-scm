<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_percent',
        'image',
       
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function bom() 
    {
        return $this->hasOne(BOM::class);
    }
}
