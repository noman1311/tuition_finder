<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'subject_expertise',
        'experience',
        'current_education_institution',
        'location',
        'coins',
        'preferred_type',
        'description',
        'image',
    ];

    // Store subject_expertise as JSON string; cast to array when reading
    protected $casts = [
        'subject_expertise' => 'array',
        'experience' => 'int',
        'coins' => 'int',
    ];
}
