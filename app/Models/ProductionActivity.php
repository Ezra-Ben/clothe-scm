<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductionActivity extends Model
{
    use HasFactory;
    protected $fillable = ['batch_id', 'description', 'type', 'timestamp'];
    protected $dates = ['timestamp'];
    protected $casts = ['timestamp' => 'datetime'
];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}