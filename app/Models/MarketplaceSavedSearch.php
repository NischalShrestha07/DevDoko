<?php
// app/Models/MarketplaceSavedSearch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceSavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'filters',
        'notification_frequency',
        'last_notified_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'last_notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shouldNotify()
    {
        if (!$this->notification_frequency || !$this->last_notified_at) {
            return true;
        }

        return match ($this->notification_frequency) {
            'instant' => true,
            'daily' => $this->last_notified_at->isBefore(now()->subDay()),
            'weekly' => $this->last_notified_at->isBefore(now()->subWeek()),
            default => false,
        };
    }
}
