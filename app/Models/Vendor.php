<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{    
     public function user()
     {
     return $this->belongsTo(User::class);
     }

     public function supplier()
     {
     return $this->hasOne(Supplier::class, 'vendor_id');
     }
     
      protected $fillable = [
	'user_id',
        'name',
	'business_name',
        'registration_number',
        'contact',
        'product_category',
        'business_license_url',
    ];


}
