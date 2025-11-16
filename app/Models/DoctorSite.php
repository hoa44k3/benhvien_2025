<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'bio',
        'rating_count',
        'average_rating',
        'experience_years',
        'specialization',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

// public function user()
// {
//     return $this->belongsTo(User::class);
// }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
