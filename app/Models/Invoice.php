<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'appointment_id',
        'medical_record_id',
        'prescription_id',
        'total',
        'status',
        'payment_method',
        'paid_at',
        'refund_amount',
        'created_by',
        'updated_by',
        'note',
        'prescription_id'  ,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
// 3. Người tạo hóa đơn (Thu ngân/Admin)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
