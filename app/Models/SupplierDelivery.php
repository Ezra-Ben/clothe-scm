<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\ProcurementRequest;
class SupplierDelivery extends Model
{
    protected $fillable = ['supplier_id', 'procurement_request_id', 'delivered_quantity', 'delivered_at'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function procurementRequest() { return $this->belongsTo(ProcurementRequest::class); }
}