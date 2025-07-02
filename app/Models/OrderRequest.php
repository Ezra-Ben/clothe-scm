<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;


    // protected $table = 'your_custom_table_name';

    protected $fillable = [
        'customer_name',
        'status',
       
    ];

    
}
