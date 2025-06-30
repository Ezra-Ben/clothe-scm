<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementDelivery extends Model
{
    protected $fillable = ['procurement_request_id', 'supplier_id', 'delivered_quantity', 'delivered_at'];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}