<?php

namespace App\Models;

use App\Models\Candidates as ModelsCandidates;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'google_id',  // ✅ Tambahkan ini
        'avatar',
        'id_roles', // Keep for custom relation
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ===== PASSWORD RESET =====
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ===== RELATIONSHIPS =====
    public function company()
    {
        return $this->hasOne(Companies::class, 'user_id', 'id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidates::class, 'user_id', 'id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidates::class);
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'user_id');
    }

    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'user_id');
    }

    public function blockedBy()
    {
        return $this->hasMany(Blacklist::class, 'blocked_user_id');
    }

    // ✅ CUSTOM ROLE RELATION (for backward compatibility)
    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'id_roles', 'id');
    }

    // ===== ✅ ROLE CHECKER METHODS (FIXED) =====

    /**
     * Check if user has specific role using custom relation OR Spatie
     */
    public function hasRoleCustom($roleName)
    {
        // Try Spatie first
        if ($this->hasRole($roleName)) {
            return true;
        }

        // Fallback to custom relation
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is a company
     */
    public function isCompany()
    {
        return $this->hasRole('company') || ($this->role && $this->role->name === 'company');
    }

    /**
     * Check if user is a regular user/candidate
     */
    public function isUser()
    {
        return $this->hasRole('user') || ($this->role && $this->role->name === 'user');
    }

    /**
     * Check if user is a candidate (alias for isUser)
     */
    public function isCandidate()
    {
        return $this->isUser();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin') || ($this->role && $this->role->name === 'super_admin');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->isSuperAdmin();
    }

    // ===== FILAMENT ACCESS =====
    public function canAccessPanel(Panel $panel): bool
    {
        // Admin panel - hanya untuk super_admin
        if ($panel->getId() === 'admin') {
            return $this->isSuperAdmin();
        }

        // Company panel - untuk company role
        if ($panel->getId() === 'company') {
            return $this->isCompany();
        }

        // User panel - untuk user/candidate role
        if ($panel->getId() === 'user') {
            return $this->isUser();
        }

        return true;
    }
}
