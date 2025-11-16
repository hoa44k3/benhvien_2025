<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Category extends Model
{
   use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image_path', 'description', 'status'
    ];

    // Tạo slug tự động khi set name (nếu cần)
    public static function booted()
    {
        static::saving(function ($category) {
            if (empty($category->slug) && !empty($category->name)) {
                $category->slug = Str::slug($category->name . '-' . uniqid());
            }
        });
    }
    public function services()
{
    return $this->hasMany(Service::class);
}

}
