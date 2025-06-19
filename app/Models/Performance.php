<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $fillable=['supplier_id,performance_note','rating','created_by'];
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
}
