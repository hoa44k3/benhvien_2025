<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Service extends Model
{
use HasFactory;
    protected $fillable = [
         'name',
        'description',
        'content',
        'fee',
        'duration',
        'status',
        'category_id',
        'department_id',
        'image',
    ];

    public function category()
{
    return $this->belongsTo(Category::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}
public function steps()
    {
        // Phải là 'step_order' thay vì 'order'
        return $this->hasMany(ServiceStep::class)->orderBy('step_order', 'asc');
    }
}
