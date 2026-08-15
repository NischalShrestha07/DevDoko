<?php

// app/Http/Controllers/MarketplaceReviewController.php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use App\Models\MarketplaceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceReviewController extends Controller
{
    /**
     * Store a review for a listing (buyer only, one completed interest required)
     */
    public function store(Request $request, MarketplaceListing $listing)
    {
        $hasCompletedInterest = $listing->interests()
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->exists();

        if (! $hasCompletedInterest) {
            abort(403, 'You can only review listings you have completed a purchase for.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'criteria' => 'nullable|array',
            'criteria.communication' => 'nullable|integer|min:1|max:5',
            'criteria.shipping' => 'nullable|integer|min:1|max:5',
            'criteria.accuracy' => 'nullable|integer|min:1|max:5',
        ]);

        $existing = MarketplaceReview::where('listing_id', $listing->id)
            ->where('buyer_id', Auth::id())
            ->exists();

        if ($existing) {
            return redirect()->back()->withErrors([
                'review' => 'You have already reviewed this listing.',
            ]);
        }

        MarketplaceReview::create([
            'listing_id' => $listing->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $listing->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'criteria' => $request->criteria,
        ]);

        return redirect()->back()->with('success', 'Review submitted. Thank you!');
    }
}
