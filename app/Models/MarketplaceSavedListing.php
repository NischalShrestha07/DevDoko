<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceSavedListing extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
    ];
}
