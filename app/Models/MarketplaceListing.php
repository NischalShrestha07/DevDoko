<?php

// app/Models/MarketplaceListing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'marketplace_listings';

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'slug',
        'description',
        'price',
        'price_type',
        'condition',
        'brand',
        'model',
        'specifications',
        'location',
        'is_shippable',
        'is_local_pickup',
        'status',
        'interested_count',
        'expires_at',
    ];

    protected $casts = [
        'specifications' => 'array',
        'metadata' => 'array',
        'is_shippable' => 'boolean',
        'is_local_pickup' => 'boolean',
        'is_featured' => 'boolean',
        'is_boosted' => 'boolean',
        'price' => 'float',
        'expires_at' => 'datetime',
        'boosted_until' => 'datetime',
    ];

    protected $appends = [
        'formatted_price',
        'time_ago',
        'condition_label',
    ];

    protected $hidden = [
        'specifications',
        'metadata',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if (empty($listing->slug)) {
                $listing->slug = self::generateUniqueSlug($listing->title);
            }
            $listing->expires_at = $listing->expires_at ?? now()->addDays(30);
        });

        static::updating(function ($listing) {
            if ($listing->isDirty('title') && ! $listing->isDirty('slug')) {
                $listing->slug = self::generateUniqueSlug($listing->title, $listing->id);
            }
        });

        static::forceDeleted(function ($listing) {
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }

    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = self::withTrashed()->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $query = self::withTrashed()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $counter++;
        }

        return $slug;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(MarketplaceListingImage::class, 'listing_id')->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(MarketplaceListingImage::class, 'listing_id')->where('is_primary', true);
    }

    public function interests()
    {
        return $this->hasMany(MarketplaceInterest::class, 'listing_id');
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'marketplace_saved_listings', 'listing_id', 'user_id')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(MarketplaceReview::class, 'listing_id');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        if ($this->price_type === 'free') {
            return 'Free';
        }

        return 'Rs '.number_format($this->price, 2);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsSavedAttribute()
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->savedBy->contains('id', Auth::id());
    }

    public function getConditionLabelAttribute()
    {
        return match ($this->condition) {
            'new' => 'New',
            'like_new' => 'Like New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            default => null,
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function scopeSearch($query, $term)
    {
        $term = str_replace(['%', '_'], ['\\%', '\\_'], $term);

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('category', 'LIKE', "%{$term}%");
        });
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function canEdit($userId)
    {
        return $this->user_id === $userId;
    }

    public static function getUniqueCategories()
    {
        return self::where('status', 'active')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }
}
