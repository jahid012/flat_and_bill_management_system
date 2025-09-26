<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class HouseOwner extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active',
        'created_by',
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
     * Get the admin who created this house owner.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Get the buildings owned by this house owner.
     */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Get all flats across all buildings owned by this house owner.
     */
    public function flats(): HasMany
    {
        return $this->hasMany(Flat::class, 'house_owner_id');
    }

    /**
     * Get all bills for flats owned by this house owner.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'created_by');
    }

    /**
     * Get all tenants across all flats owned by this house owner.
     */
    public function tenants(): HasManyThrough
    {
        return $this->hasManyThrough(Tenant::class, Flat::class, 'house_owner_id', 'flat_id');
    }
}
