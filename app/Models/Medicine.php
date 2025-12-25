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
        // 'stock',
        // 'min_stock',
        // 'price',
        // 'expiry_date',
        // 'status',
        // 'supplier',
        'medicine_category_id',
        'medicine_unit_id',
    ];
   
    // protected $fillable = ['name', 'quantity', 'threshold', 'status'];
// Liên kết với bảng Phân loại
  // ĐỔI TÊN HÀM: category -> medicineCategory
    public function medicineCategory()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }

    // ĐỔI TÊN HÀM: unit -> medicineUnit
    public function medicineUnit()
    {
        return $this->belongsTo(MedicineUnit::class, 'medicine_unit_id');
    }
}
