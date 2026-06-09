<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use HasFactory, HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'donation_category_id',
        'donor_name',
        'amount',
        'proof_image',
        'message',
        'status',
        'rejection_note',
        'approved_by',
        'approved_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Scope to filter by status.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter approved donations.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if the donor is anonymous.
     */
    public function isAnonymous(): bool
    {
        return $this->donor_name === null;
    }

    /**
     * Get display name (donor name or "Anonim").
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->donor_name ?? 'Anonim';
    }

    /**
     * Get the donation category.
     *
     * @return BelongsTo<DonationCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    /**
     * Get the user who approved this donation.
     *
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the linked finance record (created on approval).
     *
     * @return HasOne<FinanceRecord, $this>
     */
    public function financeRecord(): HasOne
    {
        return $this->hasOne(FinanceRecord::class);
    }
}
