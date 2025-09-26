<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the house owners created by this admin.
     */
    public function houseOwners(): HasMany
    {
        return $this->hasMany(HouseOwner::class, 'created_by');
    }

    /**
     * Get the tenants assigned by this admin.
     */
    public function assignedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'assigned_by');
    }
}
