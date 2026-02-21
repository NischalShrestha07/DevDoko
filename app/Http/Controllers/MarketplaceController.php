<?php
// app/Http/Controllers/MarketplaceController.php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use App\Models\MarketplaceInterest;
use App\Models\MarketplaceListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MarketplaceController extends Controller
{
    /**
     * Display marketplace homepage with listings
     */
    public function index(Request $request)
    {
        $query = MarketplaceListing::with(['user.profile', 'images'])
            ->where('status', 'active')
            ->where('expires_at', '>', now());

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('category', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Condition filter
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $listings = $query->paginate(12);

        // Get unique categories for filter sidebar
        $categories = MarketplaceListing::getUniqueCategories();

        // Get featured listings
        $featuredListings = MarketplaceListing::with(['user.profile', 'images'])
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->where(function ($q) {
                $q->where('is_featured', true)
                    ->orWhere('is_boosted', true);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('marketplace.index', compact('listings', 'categories', 'featuredListings'));
    }

    /**
     * Show form to create a new listing
     */
    public function create()
    {
        $conditions = [
            'new' => 'New',
            'like_new' => 'Like New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
        ];

        $priceTypes = [
            'fixed' => 'Fixed Price',
            'negotiable' => 'Negotiable',
            'free' => 'Free',
        ];

        return view('marketplace.create', compact('conditions', 'priceTypes'));
    }

    /**
     * Store a new listing
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required_if:price_type,fixed,negotiable|nullable|numeric|min:0',
            'price_type' => 'required|in:fixed,negotiable,free',
            'condition' => 'nullable|in:new,like_new,good,fair,poor',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_shippable' => 'nullable|boolean',
            'is_local_pickup' => 'nullable|boolean',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'active';
        $data['price'] = $data['price_type'] === 'free' ? 0 : ($data['price'] ?? 0);

        // Create listing
        $listing = MarketplaceListing::create($data);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('marketplace/' . $listing->id, 'public');

                $listing->images()->create([
                    'image_path' => $path,
                    'thumbnail_path' => $path,
                    'order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('marketplace.show', $listing->slug)
            ->with('success', 'Listing created successfully!');
    }

    /**
     * Show a single listing
     */
    public function show($slug)
    {
        $listing = MarketplaceListing::with([
            'user.profile',
            'images'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $listing->incrementViews();

        // Get similar listings based on category
        $similarListings = MarketplaceListing::with(['user.profile', 'images'])
            ->where('status', 'active')
            ->where('category', $listing->category)
            ->where('id', '!=', $listing->id)
            ->where('expires_at', '>', now())
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Get seller's other listings
        $sellerListings = MarketplaceListing::with('images')
            ->where('status', 'active')
            ->where('user_id', $listing->user_id)
            ->where('id', '!=', $listing->id)
            ->where('expires_at', '>', now())
            ->latest()
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('listing', 'similarListings', 'sellerListings'));
    }

    /**
     * Show form to edit listing
     */
    public function edit(MarketplaceListing $listing)
    {
        if (!$listing->canEdit(Auth::id())) {
            abort(403);
        }

        $conditions = [
            'new' => 'New',
            'like_new' => 'Like New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
        ];

        $priceTypes = [
            'fixed' => 'Fixed Price',
            'negotiable' => 'Negotiable',
            'free' => 'Free',
        ];

        return view('marketplace.edit', compact('listing', 'conditions', 'priceTypes'));
    }

    /**
     * Update listing
     */
    public function update(Request $request, MarketplaceListing $listing)
    {
        if (!$listing->canEdit(Auth::id())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required_if:price_type,fixed,negotiable|nullable|numeric|min:0',
            'price_type' => 'required|in:fixed,negotiable,free',
            'condition' => 'nullable|in:new,like_new,good,fair,poor',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_shippable' => 'nullable|boolean',
            'is_local_pickup' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['price'] = $data['price_type'] === 'free' ? 0 : ($data['price'] ?? 0);

        $listing->update($data);

        return redirect()->route('marketplace.show', $listing->slug)
            ->with('success', 'Listing updated successfully!');
    }

    /**
     * Delete listing
     */
    public function destroy(MarketplaceListing $listing)
    {
        if (!$listing->canEdit(Auth::id())) {
            abort(403);
        }

        // Delete images from storage
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $listing->delete();

        return redirect()->route('marketplace.index')
            ->with('success', 'Listing deleted successfully!');
    }

    /**
     * Express interest in a listing
     */
    public function expressInterest(Request $request, MarketplaceListing $listing)
    {
        if ($listing->user_id === Auth::id()) {
            return response()->json(['error' => 'You cannot express interest in your own listing'], 400);
        }

        if (!in_array($listing->status, ['active', 'reserved'])) {
            return response()->json(['error' => 'This listing is not available'], 400);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'nullable|string|max:1000',
            'offered_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $interest = MarketplaceInterest::updateOrCreate(
            [
                'listing_id' => $listing->id,
                'user_id' => Auth::id(),
            ],
            [
                'message' => $request->message,
                'offered_price' => $request->offered_price,
                'status' => 'pending',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Interest expressed successfully',
            'interest' => $interest,
        ]);
    }

    /**
     * Handle interest response (accept/decline)
     */
    public function respondToInterest(Request $request, MarketplaceInterest $interest)
    {
        if ($interest->listing->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:accept,decline',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->action === 'accept') {
            $interest->accept();
        } else {
            $interest->decline();
        }

        return response()->json([
            'success' => true,
            'message' => 'Interest ' . $request->action . 'ed successfully',
        ]);
    }

    /**
     * Save/unsave listing
     */
    public function toggleSave(MarketplaceListing $listing)
    {
        $user = Auth::user();

        if ($user->savedMarketplaceListings()->where('listing_id', $listing->id)->exists()) {
            $user->savedMarketplaceListings()->detach($listing->id);
            $saved = false;
        } else {
            $user->savedMarketplaceListings()->attach($listing->id);
            $saved = true;
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
        ]);
    }


    /**
     * Get user's listings
     */
    public function myListings()
    {
        $listings = MarketplaceListing::with(['images', 'interests'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('marketplace.my-listings', compact('listings'));
    }

    /**
     * Get user's interests (items they're interested in)
     */
    public function myInterests()
    {
        $interests = MarketplaceInterest::with(['listing', 'listing.images', 'listing.user.profile'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('marketplace.my-interests', compact('interests'));
    }

    /**
     * Get user's saved listings
     */
    public function savedListings()
    {
        $listings = Auth::user()->savedMarketplaceListings()
            ->with(['user.profile', 'images'])
            ->paginate(12);

        return view('marketplace.saved', compact('listings'));
    }

    /**
     * Category view - filter by category string
     */
    public function category($category)
    {
        $listings = MarketplaceListing::with(['user.profile', 'images'])
            ->where('status', 'active')
            ->where('category', $category)
            ->where('expires_at', '>', now())
            ->paginate(12);

        $categories = MarketplaceListing::getUniqueCategories();

        return view('marketplace.index', compact('listings', 'categories'))
            ->with('selectedCategory', $category);
    }

    public function toggleSaveById($id)
    {
        $listing = MarketplaceListing::findOrFail($id);
        return $this->toggleSave($listing);
    }

    /**
     * Express interest by ID (for AJAX calls)
     */
    public function expressInterestById(Request $request, $id)
    {
        $listing = MarketplaceListing::findOrFail($id);
        return $this->expressInterest($request, $listing);
    }
}
