<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
     use HasFactory;

    protected $fillable = [
         'code',
    'user_id',
    'doctor_id',
    'department_id',
    'patient_code',
    'patient_name',
    'patient_phone',
    'reason',
    'diagnosis',
    'notes',
    'date',
    'time',
    'status',
    'approved_by',
    'checked_in_by'

    ];
    // Quan hệ: mỗi lịch hẹn thuộc về 1 bác sĩ (nhân viên)
    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }
   public function department()
{
    return $this->belongsTo(Department::class,'department_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
  public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function checkinUser()
{
    return $this->belongsTo(User::class, 'checked_in_by');
}
// format giờ hiện thị nếu muốn 
public function getTimeAttribute($value)
{
    return Carbon::parse($value)->format('H:i');
}

}
