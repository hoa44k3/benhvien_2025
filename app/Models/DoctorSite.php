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
        'degree',
        'license_number',
        'license_issued_by',
        'license_image',

        
    ];
/**
     * Relationship: Bác sĩ có nhiều đánh giá
     */
    public function reviews()
    {
        // 'doctor_id' là cột trong bảng reviews
        // 'user_id' là cột liên kết trong bảng doctor_sites (vì doctor_id trong reviews lưu id của user)
        return $this->hasMany(Review::class, 'doctor_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function doctorSite()
    {
        // Quan hệ 1-1: User hasOne DoctorSite
        return $this->hasOne(DoctorSite::class, 'user_id');
    }
}
