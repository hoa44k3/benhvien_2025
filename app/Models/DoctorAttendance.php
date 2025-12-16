<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DoctorAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'note',
        'shift',
        'working_hours',
        'overtime_hours',
        'total_hours'

    ];

// LIÊN KẾT VỚI BẢNG USERS
    public function user()
    {
        // doctor_id là khóa ngoại, id là khóa chính của bảng users
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    
}
