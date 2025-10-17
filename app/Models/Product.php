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
        'location_address',
        'latitude',
        'longitude',
        'is_active',
        'view_count',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'latitude' => 'decimal:4',
        'longitude' => 'decimal:4',
        'is_active' => 'boolean',
        'location_address' => 'array',
    ];

    protected $appends = [];

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    public function getCoordinatesAttribute(): array
    {
        return [
            'lat' => $this->latitude ? (float) $this->latitude : 0,
            'lng' => $this->longitude ? (float) $this->longitude : 0,
        ];
    }

    public function setCoordinatesAttribute(?array $value): void
    {
        if (is_array($value)) {
            $this->attributes['latitude'] = $value['lat'] ?? null;
            $this->attributes['longitude'] = $value['lng'] ?? null;
        }
    }

    public function getLocationAddressAttribute($value)
    {
        if (is_string($value)) {
            return [
                'formatted_address' => $value,
                'address_components' => [],
                'geometry' => [
                    'location' => [
                        'lat' => $this->latitude ?? 0,
                        'lng' => $this->longitude ?? 0,
                    ]
                ]
            ];
        }
        
        return $value ?: [];
    }

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
        if (is_string($this->images)) {
            $images = json_decode($this->images, true);
            return is_array($images) ? ($images[0] ?? null) : null;
        }
        
        return is_array($this->images) ? ($this->images[0] ?? null) : null;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'available');
    }

    public function scopeNearby($query, $lat, $lng, $radius = 50)
    {
        return $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }

    public function scopeWithinRadius($query, float $latitude, float $longitude, float $radiusKm = 50)
    {
        $earthRadiusKm = 6371;

        return $query->selectRaw("
                *,
                (
                    {$earthRadiusKm} * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance_km
            ", [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km');
    }

    public function getFormattedLocationAttribute(): string
    {
        if ($this->location_address) {
            return $this->location_address;
        }

        if ($this->location) {
            return $this->location;
        }

        if ($this->latitude && $this->longitude) {
            return sprintf('%.4f, %.4f', $this->latitude, $this->longitude);
        }

        return 'Location not set';
    }

    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && 
               !is_null($this->longitude) && 
               $this->latitude != 0 && 
               $this->longitude != 0;
    }

    public function distanceTo(float $lat, float $lng): ?float
    {
        if (!$this->hasCoordinates()) {
            return null;
        }

        $earthRadiusKm = 6371;

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadiusKm, 2);
    }

    public function distanceToInMiles(float $lat, float $lng): ?float
    {
        $distanceKm = $this->distanceTo($lat, $lng);
        
        return $distanceKm ? round($distanceKm * 0.621371, 2) : null;
    }

    public function scopeOrderByDistance($query, float $latitude, float $longitude, string $direction = 'asc')
    {
        $earthRadiusKm = 6371;

        return $query->selectRaw("
                *,
                (
                    {$earthRadiusKm} * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance_km
            ", [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('distance_km', $direction);
    }

    public function getGoogleMapsLinkAttribute(): ?string
    {
        if (!$this->hasCoordinates()) {
            return null;
        }

        return sprintf(
            'https://www.google.com/maps/search/?api=1&query=%s,%s',
            $this->latitude,
            $this->longitude
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->user_id) {
                $product->user_id = auth()->id();
            }
        });
    }
}