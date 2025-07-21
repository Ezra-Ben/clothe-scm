<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'capacity_units_per_hour',
        'status',
    ];

    public function assignments()
    {
        return $this->hasMany(ResourceAssignment::class);
    }
}