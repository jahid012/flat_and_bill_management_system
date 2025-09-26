<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_active',
        'building_id',
        'flat_id',
        'assigned_by',
        'lease_start_date',
        'lease_end_date',
        'security_deposit',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'security_deposit' => 'decimal:2',
    ];

    /**
     * Get the building this tenant is assigned to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the flat this tenant is assigned to.
     */
    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    /**
     * Get the admin who assigned this tenant.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_by');
    }

    /**
     * Get the house owner through the building relationship.
     */
    public function houseOwner(): HasOneThrough
    {
        return $this->hasOneThrough(HouseOwner::class, Building::class, 'id', 'id', 'building_id', 'house_owner_id');
    }

    /**
     * Get all bills for this tenant's flat.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'flat_id', 'flat_id');
    }

    /**
     * Get the status attribute (for backward compatibility).
     * Maps is_active boolean to active/inactive string.
     */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }
}
