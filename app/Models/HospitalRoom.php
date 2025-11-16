<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Users;

class HospitalRoom extends Model
{
   use HasFactory;

    protected $fillable = [
        'department_id',
        'room_code',
        'room_type',
        'total_beds',
        'occupied_beds',
        'status',
        'patient_names',
        'available_beds',
        'user_ids',
    ];
// Một phòng thuộc về một khoa
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    protected $casts = [
        'user_ids' => 'array', // Laravel sẽ tự decode JSON sang mảng
    ];

//     // Giường còn trống
    public function getAvailableBedsAttribute()
    {
        return $this->total_beds - $this->occupied_beds;
    }

      public function getUsersAttribute()
    {
        return User::whereIn('id', $this->user_ids ?? [])->get();
    }

}
