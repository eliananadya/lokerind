<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'users_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'users_id');
    }

    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'users_id');
    }

    public function blacklistedBy()
    {
        return $this->hasMany(Blacklist::class, 'blocked_user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'users_id');
    }

    // role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCompany()
    {
        return $this->role === 'company';
    }

    public function isCandidate()
    {
        return $this->role === 'candidate';
    }

    public function getProfile()
    {
        if ($this->isCandidate()) {
            return $this->candidate;
        } elseif ($this->isCompany()) {
            return $this->company;
        }
        return null;
    }

    public function hasBlacklisted($userId)
    {
        return $this->blacklists()->where('blocked_user_id', $userId)->exists();
    }

    public function isBlacklistedBy($userId)
    {
        return $this->blacklistedBy()->where('users_id', $userId)->exists();
    }

    public function getName()
    {
        $profile = $this->getProfile();
        return $profile ? $profile->name : $this->email;
    }
}
