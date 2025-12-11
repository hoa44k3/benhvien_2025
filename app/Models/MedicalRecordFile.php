<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MedicalRecordFile extends Model
{
    use HasFactory;

    protected $fillable = [
       'medical_record_id',
        'file_path',
        'original_name',
        'file_type',
        'mime_type',
        'file_size',
        'title',
        'description',
        'uploaded_by',
        'status',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
