<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TestResult extends Model
{
  use HasFactory;
protected $dates = ['date'];
    protected $fillable = [
         'user_id','doctor_id','department_id',
    'lab_name','test_name','date','result',
    'unit','normal_range','evaluation','note',
    'file_main','status','created_by'
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function doctor()
    // {
    //     return $this->belongsTo(User::class, 'doctor_id');
    // }

    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'department_id');
    // }
public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id');
    }
    // Bệnh nhân
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Bác sĩ phụ trách
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Khoa
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Người tạo (admin / bác sĩ)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
