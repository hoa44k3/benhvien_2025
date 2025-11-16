<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Prescription extends Model
{
  use HasFactory;

    protected $fillable = [
        'code',
        'appointment_id',
        'doctor_id',
        'patient_id',
        'diagnosis',
        'note',
        'status',
    ];

    // 🔹 Mối quan hệ
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
