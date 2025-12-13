<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
   use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'medicine_name',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'price',
        'strength',
        'unit',
        'times_per_day',
        
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
// Quan hệ với Bác sĩ
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Quan hệ với Bệnh nhân
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
    /**
     * 🔥 HÀM TÍNH TỔNG TIỀN TỰ ĐỘNG
     * Gọi bằng cách: $prescription->total_amount
     */
  public function getTotalAmountAttribute()
    {
        // Nếu chưa load items thì trả về 0
        if (!$this->relationLoaded('items')) {
            return 0;
        }

        // Tính tổng: (Giá * Số lượng)
        return $this->items->sum(function($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 0);
        });
    }
}
