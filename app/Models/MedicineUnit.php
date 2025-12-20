<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineUnit extends Model
{ 
    use HasFactory;

    protected $table = 'medicine_units';

    protected $fillable = [
        'name',
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'medicine_unit_id');
    }
}
