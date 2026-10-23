<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     */
    public function index(Request $request)
    {
        $query = Destination::where('is_active', true)
            ->with(['reviews', 'images'])
            ->withCount('reviews as total_reviews')
            ->withAvg('reviews as rating', 'rating')
            ->latest();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%")
                  ->orWhere('province', 'like', "%$search%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Location filters
        if ($request->has('city') && !empty($request->city)) {
            $query->where('city', $request->city);
        }

        if ($request->has('province') && !empty($request->province)) {
            $query->where('province', $request->province);
        }

        // Rating filter
        if ($request->has('min_rating') && is_numeric($request->min_rating) && $request->min_rating > 0) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->select(DB::raw('AVG(rating) as avg_rating'))
                  ->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Additional filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'popular':
                    $query->orderBy('total_reviews', 'desc');
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        $destinations = $query->paginate(12);

        return view('pages.destinations.index', compact('destinations'));
    }

    /**
     * Get destinations for autocomplete
     */
    public function autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        $query = $request->input('query');
        
        $destinations = Destination::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('city', 'like', "%$query%")
                  ->orWhere('province', 'like', "%$query%");
            })
            ->select('id', 'name', 'city', 'province', 'image as thumbnail')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->name . ', ' . $item->city . ', ' . $item->province,
                    'name' => $item->name,
                    'city' => $item->city,
                    'province' => $item->province,
                    'thumbnail' => $item->thumbnail ? asset('storage/' . $item->thumbnail) : null
                ];
            });

        return response()->json($destinations);
    }

    /**
     * Get filter options
     */
    public function filterOptions()
    {
        $cities = Destination::where('is_active', true)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $provinces = Destination::where('is_active', true)
            ->select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        $priceRange = [
            'min' => (int)Destination::where('is_active', true)->min('price'),
            'max' => (int)ceil(Destination::where('is_active', true)->max('price') / 100000) * 100000
        ];

        return response()->json([
            'cities' => $cities,
            'provinces' => $provinces,
            'price_range' => $priceRange
        ]);
    }

    /**
     * Display the specified destination.
     */
    public function show($slug)
    {
        try {
            // Find destination by slug
            $destination = Destination::where('slug', $slug)
                ->with(['reviews.user', 'images'])
                ->firstOrFail();
                
            // Check if destination is active
            if (!$destination->is_active && !(auth()->check() && auth()->user()->isAdmin())) {
                abort(404);
            }
            
            // Get formatted facilities from the accessor and ensure it's always a collection
            $formattedFacilities = collect([]);
            if (method_exists($destination, 'getFormattedFacilitiesAttribute')) {
                $formattedFacilities = $destination->formatted_facilities;
                if (!($formattedFacilities instanceof \Illuminate\Support\Collection)) {
                    $formattedFacilities = collect($formattedFacilities ?: []);
                }
            }
            
            // Get similar destinations
            $similarDestinations = Destination::where('id', '!=', $destination->id)
                ->where(function($query) use ($destination) {
                    $query->where('city', $destination->city)
                          ->orWhere('province', $destination->province);
                })
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
                
            // Get reviews
            $reviews = $destination->reviews()
                ->with('user')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($review) {
                    $fullStars = floor($review->rating);
                    $hasHalfStar = ($review->rating - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                    
                    return [
                        'id' => $review->id,
                        'user' => [
                            'name' => $review->user->name,
                            'avatar' => $review->user->avatar_url ?? null,
                        ],
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'date' => $review->created_at->format('d F Y'),
                        'full_stars' => $fullStars,
                        'has_half_star' => $hasHalfStar,
                        'empty_stars' => $emptyStars
                    ];
                });
                
            // Prepare data for the view
            $data = [
                'destination' => $destination,
                'formattedFacilities' => $formattedFacilities,
                'similarDestinations' => $similarDestinations,
                'reviews' => $reviews,
                'rating' => $destination->reviews->avg('rating') ?? 0,
                'total_reviews' => $destination->reviews->count(),
                'full_stars' => floor($destination->reviews->avg('rating') ?? 0),
                'has_half_star' => fmod($destination->reviews->avg('rating') ?? 0, 1) >= 0.5,
            ];
            
            return view('destinations.show', $data);
            
        } catch (\Exception $e) {
            \Log::error('Error showing destination: ' . $e->getMessage());
            abort(404);
        }
    }
}
