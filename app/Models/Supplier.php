<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['vendor_id', 'address', 'added_by', 'is_active', 'last_supplied_at'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function scopeActive($query)
    {
    return $query->where('is_active', true);
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    } public function procurementRequests()
    {
        return $this->hasMany(ProcurementRequest::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class,);
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