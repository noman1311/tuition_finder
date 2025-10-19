<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'tuition_offers';
    protected $primaryKey = 'offer_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'subject',
        'class_level',
        'location',
        'salary',
        'status',
        'description',
        'phone',
        'type',
        'preferred_type',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    // Relationships
    public function applications()
    {
        return $this->hasMany(Application::class, 'offer_id', 'offer_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
