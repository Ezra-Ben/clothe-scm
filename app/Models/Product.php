<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'type'];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function scopeRaw($query)
    {
        return $query->where('type', 'raw');
    }

    public function scopeFinished($query)
    {
        return $query->where('type', 'finished');
    }
}