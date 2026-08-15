<?php

// app/Models/MarketplaceInterest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MarketplaceInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'user_id',
        'message',
        'offered_price',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'offered_price' => 'decimal:2',
        'responded_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_offered_price',
        'status_badge',
        'status_color',
        'time_ago',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($interest) {
            $interest->listing->increment('interested_count');
        });

        static::deleted(function ($interest) {
            $interest->listing->decrement('interested_count');
        });
    }

    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class, 'listing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedOfferedPriceAttribute()
    {
        if (! $this->offered_price) {
            return null;
        }

        return 'Rs '.number_format($this->offered_price, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-warning',
            'accepted' => 'bg-success',
            'declined' => 'bg-danger',
            'completed' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'accepted' => 'success',
            'declined' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // Reject all other pending interests for this listing
        $this->listing->interests()
            ->where('id', '!=', $this->id)
            ->where('status', 'pending')
            ->update(['status' => 'declined', 'responded_at' => now()]);

        // Mark listing as reserved
        $this->listing->update(['status' => 'reserved']);

        $this->notifyBuyer('interest_accepted', 'Your interest in "'.$this->listing->title.'" was accepted!');
    }

    public function decline()
    {
        $this->update([
            'status' => 'declined',
            'responded_at' => now(),
        ]);

        $this->notifyBuyer('interest_declined', 'Your interest in "'.$this->listing->title.'" was declined.');
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
        ]);
        $this->listing->update(['status' => 'sold']);

        $this->notifyBuyer('interest_completed', 'Your purchase of "'.$this->listing->title.'" is complete. Thanks!');
    }

    private function notifyBuyer(string $type, string $message): void
    {
        $this->user->notifications()->create([
            'from_user_id' => Auth::id(),
            'type' => $type,
            'message' => $message,
            'data' => [
                'listing_id' => $this->listing_id,
                'listing_title' => $this->listing->title,
                'interest_id' => $this->id,
            ],
        ]);
    }

    public function canBeManagedBy($userId)
    {
        return $this->listing->user_id === $userId || $this->user_id === $userId;
    }
}
