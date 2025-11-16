<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'head_name',
        'num_doctors',
        'num_nurses',
        'num_rooms',
        'fee',
        'status',
        'image',
    ];
    //  * Quan hệ 1-n: Một khoa có nhiều lịch hẹn
    //  */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'department_id');
    }

    /**
     * Quan hệ 1-n: Một khoa có nhiều nhân viên (bác sĩ, y tá)
     */
    public function staff()
    {
        return $this->hasMany(Staff::class, 'department_id');
    }
    public function services()
{
    return $this->hasMany(Service::class);
}

}
