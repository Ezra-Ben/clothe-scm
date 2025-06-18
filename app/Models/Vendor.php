<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
protected $fillable = [
    'name',
    'business_name', 
    'registration_number',
    'contact',   
    'product_category',    
    'business_license_url',
];
}
