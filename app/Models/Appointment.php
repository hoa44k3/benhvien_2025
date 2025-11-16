<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
     use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'doctor_id', 'patient_name', 
        'department_id', 'time', 'date', 'status', 'reason', 'patient_phone', 'patient_code','notes','diagnosis'

    ];
    // Quan hệ: mỗi lịch hẹn thuộc về 1 bác sĩ (nhân viên)
    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }
   public function department()
{
    return $this->belongsTo(Department::class,'department_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


}
