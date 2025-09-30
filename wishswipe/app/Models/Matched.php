<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matched extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($matched) {
            // Automatically create a conversation when a matched
            Conversation::create([
                'matched_id' => $matched->id,
                'product_id' => $matched->product_id,
            ]);
        });
    }
}