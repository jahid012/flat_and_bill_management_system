<?php

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'total_floors',
        'total_flats',
        'description',
        'is_active',
        'house_owner_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_floors' => 'integer',
        'total_flats' => 'integer',
    ];

    /**
     * Get the house owner of this building.
     */
    public function houseOwner(): BelongsTo
    {
        return $this->belongsTo(HouseOwner::class);
    }

    /**
     * Get all flats in this building.
     */
    public function flats(): HasMany
    {
        return $this->hasMany(Flat::class);
    }

    /**
     * Get all tenants in this building.
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * Get all bill categories for this building.
     */
    public function billCategories(): HasMany
    {
        return $this->hasMany(BillCategory::class);
    }

    /**
     * Get all bills for this building.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
