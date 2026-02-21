<?php
// app/Http/Controllers/MarketplaceInterestController.php

namespace App\Http\Controllers;

use App\Models\MarketplaceInterest;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MarketplaceInterestController extends Controller
{
    /**
     * Show interests received for seller's listings
     */
    public function received()
    {
        $interests = MarketplaceInterest::with(['listing', 'user.profile'])
            ->whereHas('listing', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => MarketplaceInterest::whereHas('listing', function ($q) {
                $q->where('user_id', Auth::id());
            })->count(),
            'pending' => MarketplaceInterest::whereHas('listing', function ($q) {
                $q->where('user_id', Auth::id());
            })->where('status', 'pending')->count(),
            'accepted' => MarketplaceInterest::whereHas('listing', function ($q) {
                $q->where('user_id', Auth::id());
            })->where('status', 'accepted')->count(),
        ];

        return view('marketplace.interests.received', compact('interests', 'stats'));
    }

    /**
     * Show interests sent by the user (buyer)
     */
    public function sent()
    {
        $interests = MarketplaceInterest::with(['listing', 'listing.user.profile'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('marketplace.interests.sent', compact('interests'));
    }

    /**
     * Show single interest conversation
     */
    public function show(MarketplaceInterest $interest)
    {
        if (!$interest->canBeManagedBy(Auth::id())) {
            abort(403);
        }

        $interest->load(['listing', 'user.profile', 'listing.user.profile']);

        return view('marketplace.interests.show', compact('interest'));
    }

    /**
     * Update interest status (accept/decline/complete)
     */
    public function update(Request $request, MarketplaceInterest $interest)
    {
        if ($interest->listing->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:accept,decline,complete',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        switch ($request->action) {
            case 'accept':
                $interest->accept();
                $message = 'Interest accepted! The buyer has been notified.';
                break;
            case 'decline':
                $interest->decline();
                $message = 'Interest declined.';
                break;
            case 'complete':
                $interest->complete();
                $message = 'Sale completed! Thank you.';
                break;
        }

        return redirect()->route('marketplace.interests.received')
            ->with('success', $message);
    }

    /**
     * Get conversation messages (for AJAX)
     */
    public function messages(MarketplaceInterest $interest)
    {
        if (!$interest->canBeManagedBy(Auth::id())) {
            abort(403);
        }

        // You can implement a message system here
        // For now, return the interest details
        return response()->json([
            'interest' => $interest->load(['user.profile']),
        ]);
    }
}
