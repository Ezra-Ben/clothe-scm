<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    protected $fillable = ['product_id', 'quantity','requested_quantity', 'status', 'supplier_id', 'approved_by'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function scopePending($query)
{
    return $query->where('status', 'pending');
}
}