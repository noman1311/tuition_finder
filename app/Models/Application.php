<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'offer_id',
        'teacher_id',
        'status',
        'message',
    ];
}


