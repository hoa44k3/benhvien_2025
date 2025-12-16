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
        'specialization',
        'bio',
        'rating',
        'reviews_count',
        'image',
        'status',
        'experience_years',
        'base_salary',
        'commission_exam_percent',
        'commission_prescription_percent',
        'commission_service_percent',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',

        
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
