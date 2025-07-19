<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pod extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'shipment_id',        
        'shipment_type',      
        'delivered_by',       
        'received_by',  
        'received_at',        
        'delivery_notes',     
        'recipient_name',     
        'condition',          
        'discrepancies',      
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->morphTo();
    }
}