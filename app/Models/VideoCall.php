<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCall extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'duration',
        'end_time',
        'start_time',
        'patient_id',
        'doctor_id',
        'appointment_id'
    ];

    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
    public function patient() { return $this->belongsTo(User::class, 'patient_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}
