<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
protected $fillable = [
    'name',
    'registration_number',
    'email',
    'phone',
    'address',
    'previous_clients',       // JSON
    'transaction_history',    // JSON
    'industry_ratings',       // Float
    'product_categories',     // JSON
    'material_types',         // JSON
    'pricing_range',
    'bulk_availability',      // Boolean
    'certifications',         // JSON
    'business_license',
    'tax_identification',
    // Add other fields you want to be mass-assignable
];
}
