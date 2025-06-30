<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'supplier_id', 
	'contract_number', 
	'start_date', 
	'end_date', 
	'status',
        'terms', 
	'payment_terms', 
	'renewal_date', 
	'added_by', 
	'notes',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
