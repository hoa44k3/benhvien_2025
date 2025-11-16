<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
     use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'staff_code',
        'name',
        'position',
        'department_id',
        'phone',
        'email',
        'experience_years',
        'rating',
        'role_id',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }
    public function role()
{
    return $this->belongsTo(Role::class, 'role_id');
}
public function shifts()
{
    return $this->hasMany(Shift::class);
}

public function appointments()
{
    return $this->hasMany(Appointment::class);
}
// public function user()
// {
//     return $this->belongsTo(User::class, 'email', 'email');
// }
public function user()
{
    return $this->belongsTo(User::class);
}

}
