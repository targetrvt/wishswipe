<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'images',
        'condition',
        'status',
        'location',
        'latitude',
        'longitude',
        'is_active',
        'view_count',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function swipes(): HasMany
    {
        return $this->hasMany(Swipe::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(Matched::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function getFirstImageAttribute()
    {
        return $this->images[0] ?? null;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'available');
    }

    public function scopeNearby($query, $lat, $lng, $radius = 50) //velak uztaisit labaku
    {
        return $query->whereNotNull('latitude') 
            ->whereNotNull('longitude')
            ->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }
}