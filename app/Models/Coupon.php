<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Get the orders that used this coupon
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->status) {
            return false;
        }

        $today = Carbon::now()->format('Y-m-d');
        
        if ($today < $this->start_date->format('Y-m-d') || $today > $this->end_date->format('Y-m-d')) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can use this coupon
     */
    public function canBeUsedByUser($userId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsage = Order::where('user_id', $userId)
            ->where('coupon_id', $this->id)
            ->count();

        return $userUsage < $this->usage_limit_per_user;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($amount): float
    {
        if ($amount < $this->minimum_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            
            if ($this->maximum_discount && $discount > $this->maximum_discount) {
                return $this->maximum_discount;
            }
            
            return $discount;
        } else {
            // Fixed amount
            return min($this->value, $amount);
        }
    }

    /**
     * Increment used count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Get status badge attribute
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status ? 'success' : 'danger';
    }

    /**
     * Get status text attribute
     */
    public function getStatusTextAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    /**
     * Get type text attribute
     */
    public function getTypeTextAttribute(): string
    {
        return $this->type === 'percentage' ? 'Percentage' : 'Fixed Amount';
    }
}
