<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Types\Relations\Role;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Helpers\AuditHelper;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

protected $fillable = [
    'patient_code',
        'name',
        'phone',
        'last_visit',
        'status',
        'email',
        'password',
        'address',
        'cccd',
        'occupation',
        'avatar',
        'gender',
        'date_of_birth',
        'job',
        'insurance_number',
        'nationality',
        'role_id',
        'is_active',
    'age'
];

 
    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
 
// public function room()
//     {
//         return $this->belongsTo(HospitalRoom::class, 'room_id');
//     }
    public function hospitalRooms()
{
    return $this->belongsToMany(HospitalRoom::class, 'hospital_room_user', 'user_id', 'hospital_room_id');
}
// app/Models/User.php
public function appointments()
{
    return $this->hasMany(\App\Models\Appointment::class);
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected static function booted()
    {
        static::created(function ($user) {
            AuditHelper::log('Tạo tài khoản mới', $user->name, 'Thành công');
        });

        static::updated(function ($user) {
            AuditHelper::log('Cập nhật tài khoản', $user->name, 'Thành công');
        });

        static::deleted(function ($user) {
            AuditHelper::log('Xóa tài khoản', $user->name, 'Thành công');
        });
    }

    // public function doctorSite()
    // {
    //     return $this->hasOne(DoctorSite::class, 'user_id');
    // }
    public function isDoctor()
{
    return $this->roles()->where('name', 'doctor')->exists();
}

}
