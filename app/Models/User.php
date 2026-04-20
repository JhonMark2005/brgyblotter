<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'role',
    ];

    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'recorded_by');
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordResetCustom::class, 'user_id');
    }
}
