<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'phone_number',
        'phone',
        'full_name',
        'email',
        'address',
    ];

    protected $hidden = [
        'password',
    ];

    // Auto hash password ketika di-set
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Method untuk cek password
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user_id == 999; // Admin user_id
    }

    /**
     * Relationship ke transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }
}
