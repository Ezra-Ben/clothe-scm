<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Batch extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'product_id', 'quantity', 'status', 'start_date', 'end_date', 'schedule_id'];
    protected $dates = ['start_date', 'end_date'];
    protected $casts = ['start_date' => 'datetime','end_date'=> 'datetime',
];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function activities()
    {
        return $this->hasMany(ProductionActivity::class);
    }
    public function resourceAssignments()
    {
        return $this->hasMany(ResourceAssignment::class);
    }
}

