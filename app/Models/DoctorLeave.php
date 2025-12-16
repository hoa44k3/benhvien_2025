<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DoctorLeave extends Model
{
    use HasFactory;
   protected $fillable = ['user_id', 'start_date', 'end_date', 'reason', 'status', 'admin_note'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
