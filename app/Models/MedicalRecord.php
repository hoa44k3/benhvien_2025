<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MedicalRecord extends Model
{
        protected $dates = ['date', 'next_checkup']; 
   protected $fillable = [
        'user_id', 'title', 'date', 'doctor_id', 'department_id',
        'diagnosis', 'treatment', 'next_checkup', 'status', 'appointment_id', 'symptoms', 'vital_signs',
        'diagnosis_primary', 'diagnosis_secondary'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // public function prescriptions() {
    //     return $this->hasMany(Prescription::class);
    // }

    // public function testResults() {
    //     return $this->hasMany(TestResult::class);
    // }
    public function testResults()
{
    // Quan hệ 1-Nhiều: Một hồ sơ có nhiều kết quả xét nghiệm
    return $this->hasMany(TestResult::class, 'medical_record_id');
}
 // **Thêm quan hệ files**
    // public function files()
    // {
    //     return $this->hasMany(MedicalRecordFile::class); // table: medical_record_files
    // }
public function files()
{
    return $this->hasMany(MedicalRecordFile::class, 'medical_record_id');
}

public function prescriptions()
{
    // Nếu bảng prescriptions có medical_record_id
    return $this->hasMany(Prescription::class, 'medical_record_id');
}
}
