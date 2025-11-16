<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'created_at', 'shift', 'room'
    ];

    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_roles');
}

public function isDoctor()
{
    return $this->roles()->where('name', 'Bác sĩ')->exists();
}

}
