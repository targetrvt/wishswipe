<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NegotiateRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
        'proposed_price',
        'message',
        'status',
        'parent_request_id',
        'expires_at',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function parentRequest(): BelongsTo
    {
        return $this->belongsTo(NegotiateRequest::class, 'parent_request_id');
    }

    public function counterOffers(): HasMany
    {
        return $this->hasMany(NegotiateRequest::class, 'parent_request_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopeCounterOffered($query)
    {
        return $query->where('status', 'counter_offered');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canBeAccepted(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    public function canBeDeclined(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    public function canBeCounterOffered(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    public function accept(): bool
    {
        if (!$this->canBeAccepted()) {
            return false;
        }

        $this->update(['status' => 'accepted']);
        
        // Decline all other pending requests for this product
        NegotiateRequest::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->where('status', 'pending')
            ->update(['status' => 'declined']);

        return true;
    }

    public function decline(): bool
    {
        if (!$this->canBeDeclined()) {
            return false;
        }

        $this->update(['status' => 'declined']);
        return true;
    }

    public function counterOffer(float $newPrice, string $message = null): NegotiateRequest
    {
        if (!$this->canBeCounterOffered()) {
            throw new \Exception('Cannot create counter offer for this request');
        }

        $this->update(['status' => 'counter_offered']);

        return NegotiateRequest::create([
            'buyer_id' => $this->seller_id, // Role reversal
            'seller_id' => $this->buyer_id,
            'product_id' => $this->product_id,
            'proposed_price' => $newPrice,
            'message' => $message,
            'parent_request_id' => $this->id,
            'expires_at' => now()->addDays(7), // 7 days to respond
        ]);
    }
}
