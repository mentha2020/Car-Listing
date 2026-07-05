<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'make',
        'model',
        'year',
        'price',
        'mileage',
        'fuel_type',
        'transmission',
        'city',
        'description',
        'status',
        'rejection_reason',
        'is_featured',
        'featured_at',
        'featured_until',
        'views_count',
        'slug',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'is_featured' => 'boolean',
        'featured_at' => 'datetime',
        'featured_until' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Car $car) {
            if (empty($car->slug)) {
                $car->slug = static::generateSlug($car);
            }
        });

        static::updating(function (Car $car) {
            if ($car->isDirty(['make', 'model', 'year']) && !$car->isDirty('slug')) {
                $car->slug = static::generateSlug($car);
            }
        });
    }

    public static function generateSlug(Car $car): string
    {
        $base = Str::slug("{$car->make} {$car->model} {$car->year}");
        $slug = $base;
        $counter = 1;

        while (static::withoutGlobalScopes()->where('slug', $slug)->where('id', '!=', $car->id ?? 0)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function favoritedByUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Favorite::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('featured_until', '>', now());
    }

    public function scopeByMake($query, ?string $make)
    {
        return $make ? $query->where('make', $make) : $query;
    }

    public function scopeByModel($query, ?string $model)
    {
        return $model ? $query->where('model', $model) : $query;
    }

    public function scopeByYearRange($query, ?int $min, ?int $max)
    {
        if ($min) $query->where('year', '>=', $min);
        if ($max) $query->where('year', '<=', $max);
        return $query;
    }

    public function scopeByPriceRange($query, ?float $min, ?float $max)
    {
        if ($min) $query->where('price', '>=', $min);
        if ($max) $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeByFuelType($query, ?string $fuelType)
    {
        return $fuelType ? $query->where('fuel_type', $fuelType) : $query;
    }

    public function scopeByTransmission($query, ?string $transmission)
    {
        return $transmission ? $query->where('transmission', $transmission) : $query;
    }

    public function scopeByMileage($query, ?int $max)
    {
        return $max ? $query->where('mileage', '<=', $max) : $query;
    }

    public function scopeByCity($query, ?string $city)
    {
        return $city ? $query->where('city', $city) : $query;
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved', 'rejection_reason' => null]);
    }

    public function reject(string $reason): void
    {
        $this->update(['status' => 'rejected', 'rejection_reason' => $reason]);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 0);
    }
}
