<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Swipe extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'direction',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeLikes($query)
    {
        return $query->where('direction', 'right');
    }

    public function scopeDislikes($query)
    {
        return $query->where('direction', 'left');
    }

    public static function createMatch($userId, $product)
    {
        // Check if this is a right swipe
        $swipe = self::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->where('direction', 'right')
            ->first();

        if ($swipe) {
            // Create a match
            Matched::firstOrCreate([
                'buyer_id' => $userId,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
            ]);
        }
    }
}