<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
       'vendor_id', 
       'address', 
       'added_by',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }


    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}