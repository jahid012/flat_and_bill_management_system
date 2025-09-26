<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_active',
        'building_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the building this bill category belongs to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get all bills for this category.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
