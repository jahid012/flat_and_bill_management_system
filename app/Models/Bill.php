<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'bill_category_id',
        'building_id',
        'bill_month',
        'amount',
        'due_amount',
        'status',
        'due_date',
        'paid_date',
        'paid_amount',
        'notes',
        'payment_notes',
        'payment_method',
        'transaction_id',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the flat this bill belongs to.
     */
    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }

    /**
     * Get the bill category.
     */
    public function billCategory(): BelongsTo
    {
        return $this->belongsTo(BillCategory::class);
    }

    /**
     * Get the building this bill belongs to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the house owner who created this bill.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(HouseOwner::class, 'created_by');
    }

    /**
     * Get the tenant for this bill through the flat.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'flat_id', 'flat_id');
    }

    /**
     * Scope to get unpaid bills.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope to get paid bills.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to get overdue bills.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'unpaid')
                          ->where('due_date', '<', now());
                    });
    }

    /**
     * Calculate the total amount (amount + due_amount).
     */
    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->due_amount;
    }

    /**
     * Check if the bill is fully paid.
     */
    public function isFullyPaid()
    {
        return $this->status === 'paid' && $this->paid_amount >= $this->getTotalAmountAttribute();
    }

    /**
     * Mark the bill as paid.
     */
    public function markAsPaid($amount, $paymentMethod = null, $transactionId = null, $notes = null)
    {
        $this->update([
            'status' => $amount >= $this->getTotalAmountAttribute() ? 'paid' : 'partially_paid',
            'paid_amount' => $amount,
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'payment_notes' => $notes,
        ]);

        return $this;
    }
}
