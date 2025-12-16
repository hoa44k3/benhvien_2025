<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'parent_id', 'name', 'email', 'content', 'status', 'is_visible', 'approved_by', 'created_at', 'updated_at'];

    // Quan hệ lấy các câu trả lời (Reply)
    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
    
    public function post() {
        return $this->belongsTo(Post::class);
    }
     // Bình luận cha
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
