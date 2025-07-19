<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'start_date', 'end_date', 'status'];
    protected $dates = ['start_date', 'end_date'];
        protected $casts = ['start_date' => 'datetime','end_date'=> 'datetime',
];
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}