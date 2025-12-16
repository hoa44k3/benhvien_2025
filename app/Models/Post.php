<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
   use HasFactory;
    protected $fillable = ['title', 'slug', 'description', 'content', 'image', 'user_id', 'is_featured', 'status', 'views'];
// --- THÊM ĐOẠN NÀY VÀO ---
    public function author()
    {
        // Quan hệ: Bài viết thuộc về 1 User (người đăng)
        // 'user_id' là khóa ngoại trong bảng posts
        return $this->belongsTo(User::class, 'user_id');
    }
    // Quan hệ với người đăng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với bình luận (Lấy bình luận cha trước)
    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }
}
