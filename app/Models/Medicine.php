<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
     use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'stock',
        'min_stock',
        'unit',
        'price',
        'expiry_date',
        'status',
        'supplier',
    ];
   
    // protected $fillable = ['name', 'quantity', 'threshold', 'status'];

}
