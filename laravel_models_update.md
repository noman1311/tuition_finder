# Laravel Models Update for New Database Schema

## Updated User Model (app/Models/User.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'email',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
```

## New Teacher Model (app/Models/Teacher.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

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

    protected $casts = [
        'subject_expertise' => 'array',
        'coins' => 'integer',
        'experience' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
```

## New Student Model (app/Models/Student.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'class_level',
        'location',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tuitionOffers()
    {
        return $this->hasMany(TuitionOffer::class);
    }
}
```

## New Transaction Model (app/Models/Transaction.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## New TuitionOffer Model (app/Models/TuitionOffer.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'class_level',
        'location',
        'salary',
        'status',
        'description',
        'type',
        'preferred_type',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
```

## New Application Model (app/Models/Application.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'teacher_id',
        'status',
        'message',
    ];

    // Relationships
    public function tuitionOffer()
    {
        return $this->belongsTo(TuitionOffer::class, 'offer_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
```

## Database Configuration (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tuition_finder
DB_USERNAME=root
DB_PASSWORD=
```

## Steps to Update Your Laravel Application:

1. **Run the SQL queries** in phpMyAdmin to create the database and tables
2. **Update your .env file** with the MySQL database credentials
3. **Create the new model files** using the code above
4. **Update your existing models** to match the new schema
5. **Update your controllers** to work with the new relationships
6. **Test the application** to ensure everything works correctly

