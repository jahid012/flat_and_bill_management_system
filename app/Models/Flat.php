<?php

namespace App\Models;

use App\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Flat extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'flat_number',
        'floor',
        'type',
        'area_sqft',
        'rent_amount',
        'flat_owner_name',
        'flat_owner_phone',
        'flat_owner_email',
        'is_occupied',
        'is_active',
        'building_id',
        'house_owner_id',
        'current_tenant_id',
    ];

    protected $casts = [
        'is_occupied' => 'boolean',
        'is_active' => 'boolean',
        'area_sqft' => 'decimal:2',
        'rent_amount' => 'decimal:2',
        'floor' => 'integer',
    ];

    /**
     * Get the building this flat belongs to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the current tenant of this flat.
     */
    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    /**
     * Get all tenants who have lived in this flat.
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * Get all bills for this flat.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get the house owner of this flat through the building.
     */
    public function houseOwner(): BelongsTo
    {
        return $this->belongsTo(HouseOwner::class, 'house_owner_id');
    }

    /**
     * Scope to get occupied flats.
     */
    public function scopeOccupied($query)
    {
        return $query->where('is_occupied', true);
    }

    /**
     * Scope to get vacant flats.
     */
    public function scopeVacant($query)
    {
        return $query->where('is_occupied', false);
    }
}
