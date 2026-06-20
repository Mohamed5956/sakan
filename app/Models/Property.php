<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'price',
        'price_period',
        'address',
        'city',
        'district',
        'latitude',
        'longitude',
        'nearest_university',
        'bathrooms',
        'main_image',
        'whatsapp',
        'phone',
        'email',
        'meta_title',
        'meta_description',
        'is_active',
        'is_featured',
        'views',
        'user_id',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'latitude'    => 'float',
        'longitude'   => 'float',
        'price'       => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (Property $property) {

            if (empty($property->slug)) {

                $base = preg_replace('/[^\p{Arabic}\p{N}\s-]+/u', '', $property->title);
                $base = preg_replace('/\s+/u', '-', trim($base));
                $base = preg_replace('/-+/', '-', $base);

                $slug = $base;
                $count = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $count++;
                }

                $property->slug = $slug;
            }
        });
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relationships ──────────────────────────────
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ──────────────────────────────────
    public function getTotalBedsAttribute(): int
    {
        return $this->rooms->sum('beds');
    }

    public function getTotalRoomsAttribute(): int
    {
        return $this->rooms->count();
    }

    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/property-placeholder.webp');
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title . ' | سكن';
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: Str::limit(strip_tags($this->description), 160);
    }

    // ── Scopes ─────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, array $filters)
    {
        return $query
            ->when($filters['type'] ?? null,
                fn($q, $v) => $q->where('type', $v))
            ->when($filters['city'] ?? null,
                fn($q, $v) => $q->where('city', $v))
            ->when($filters['district'] ?? null,
                fn($q, $v) => $q->where('district', 'like', "%$v%"))
            ->when($filters['university'] ?? null,
                fn($q, $v) => $q->where('nearest_university', 'like', "%$v%"))
            ->when($filters['min_price'] ?? null,
                fn($q, $v) => $q->where('price', '>=', $v))
            ->when($filters['max_price'] ?? null,
                fn($q, $v) => $q->where('price', '<=', $v))
            ->when($filters['bathrooms'] ?? null,
                fn($q, $v) => $q->where('bathrooms', '>=', $v))
            ->when($filters['keyword'] ?? null,
                fn($q, $v) => $q->where(function ($q) use ($v) {
                    $q->where('title', 'like', "%$v%")
                        ->orWhere('description', 'like', "%$v%")
                        ->orWhere('address', 'like', "%$v%");
                }));
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
