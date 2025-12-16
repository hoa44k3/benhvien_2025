<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceStep extends Model
{
   // 🔥 SỬA LẠI 'order' THÀNH 'step_order'
    protected $fillable = [
        'service_id', 
        'title', 
        'description', 
        'image', 
        'step_order' // Tên chuẩn trong database
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
