<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ---------------------------------------------------------------------------
    // Role Constants
    // ---------------------------------------------------------------------------
    const ROLE_SYSTEM_ADMIN  = 1;
    const ROLE_PI            = 2;
    const ROLE_RESEARCHER    = 3;
    const ROLE_LAB_MANAGER   = 4;
    const ROLE_AUDITOR       = 5;


    // ---------------------------------------------------------------------------
    // Fillable / Hidden
    // ---------------------------------------------------------------------------
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role_id',
        'pis_id',
        'academicLevel',
        'budget_limit',
        'managed_Lab_Locations',
        'user_role_tier',
        'security_clearance_level',
        'is_active',
        'expiry_date',
        'clearance_level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'expiry_date'  => 'date',
    ];


    // Foreign ID [ user->role_id ===> roles table ]
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function certifications(){
        return $this->hasMany(Certification::class);
    }
    
    protected $with = ['role'];

    public function isResearcher(): bool
    {
        return $this->role && $this->role->name == "Researcher";
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }


    // Return iD 
    public function getID()
    {
        return $this->id;
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}