<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
      'address','name',
        'contact_person',
        'email',
        'phone',
        'lead_time_days',
        'contract_terms'
    ];

    protected $casts = [
        'lead_time_days' => 'integer'
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

     public function orders(): HasMany
    {
        return $this->hasMany(SupplierOrder::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(InboundShipment::class);
    }


}