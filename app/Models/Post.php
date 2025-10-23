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

    // Helper methods for phone number display
    public function getFormattedPhoneAttribute()
    {
        return $this->formatPhoneNumber($this->phone);
    }

    public function getHiddenPhoneAttribute()
    {
        return $this->formatPhoneNumber($this->phone, true);
    }

    public function formatPhoneNumber($phone, $hide = false)
    {
        $phoneStr = (string) $phone;
        
        if (!$hide) {
            // Return full phone number
            return $phoneStr;
        }

        // Return partially hidden phone number
        $previewDigits = config('tuition.phone_preview_digits', 5);
        $hiddenChar = config('tuition.phone_hidden_char', '•');
        
        if (strlen($phoneStr) <= $previewDigits) {
            return $phoneStr;
        }

        $preview = substr($phoneStr, 0, $previewDigits);
        $hiddenPart = str_repeat($hiddenChar, strlen($phoneStr) - $previewDigits);
        
        return $preview . $hiddenPart;
    }

    public function hasAppliedBy($teacherId)
    {
        return $this->applications()->where('teacher_id', $teacherId)->exists();
    }
}
