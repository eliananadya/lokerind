<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role', // 'admin', 'company', 'candidate'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */

    // User -> Candidate (One to One)
    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'users_id');
    }

    // User -> Company (One to One)
    public function company()
    {
        return $this->hasOne(Company::class, 'users_id');
    }

    // User -> Blacklists (blacklist yang dibuat user ini)
    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'users_id');
    }

    // User -> Blacklisted By (user yang memblokir user ini)
    public function blacklistedBy()
    {
        return $this->hasMany(Blacklist::class, 'blocked_user_id');
    }

    // User -> Reports (laporan yang dibuat user ini)
    public function reports()
    {
        return $this->hasMany(Report::class, 'users_id');
    }

    /**
     * Helper Methods
     */

    // Cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Cek apakah user adalah company
    public function isCompany()
    {
        return $this->role === 'company';
    }

    // Cek apakah user adalah candidate
    public function isCandidate()
    {
        return $this->role === 'candidate';
    }

    // Get profile berdasarkan role (dinamis)
    public function getProfile()
    {
        if ($this->isCandidate()) {
            return $this->candidate;
        } elseif ($this->isCompany()) {
            return $this->company;
        }
        return null;
    }

    // Cek apakah user sudah blacklist user lain
    public function hasBlacklisted($userId)
    {
        return $this->blacklists()->where('blocked_user_id', $userId)->exists();
    }

    // Cek apakah user sudah di-blacklist oleh user lain
    public function isBlacklistedBy($userId)
    {
        return $this->blacklistedBy()->where('users_id', $userId)->exists();
    }

    // Get nama user (dari profile candidate atau company)
    public function getName()
    {
        $profile = $this->getProfile();
        return $profile ? $profile->name : $this->email;
    }
}
