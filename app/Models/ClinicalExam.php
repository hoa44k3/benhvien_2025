<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalExam extends Model
{
   use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'entered_by',
        'temperature',
        'blood_pressure',
        'pulse',
        'respiratory_rate',
        'spo2',
        'weight',
        'height',
        'bmi',
        'exam_type',
        'notes',
        'measurements',
    ];

    protected $casts = [
        'measurements' => 'array',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
}
