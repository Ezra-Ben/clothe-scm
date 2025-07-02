<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatusHistory extends Model
{
        protected $fillable = ['delivery_id', 'status', 'changed_at', 'changed_by'];
        protected $casts =['changed_at' => 'datetime'];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
