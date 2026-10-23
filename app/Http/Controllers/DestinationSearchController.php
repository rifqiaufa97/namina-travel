<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DestinationSearchController extends Controller
{
    /**
     * Show the search page
     */
    public function index()
    {
        // Get distinct cities and provinces for filters
        $cities = Destination::select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter()
            ->values();

        $provinces = Destination::select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province')
            ->filter()
            ->values();

        // Get price range
        $minPrice = (int) Destination::min('price') ?: 0;
        $maxPrice = (int) ceil(Destination::max('price') / 100000) * 100000;

        // Get initial results if there's a search query
        $destinations = null;
        if (request()->has('q') || request()->hasAny(['city', 'province', 'min_rating', 'min_price', 'max_price'])) {
            $query = $this->buildSearchQuery(request());
            $destinations = $query->paginate(12)->withQueryString();
        }

        return view('destinations.search', [
            'cities' => $cities,
            'provinces' => $provinces,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'destinations' => $destinations,
        ]);
    }

    /**
     * Handle search API requests
     */
    public function search(Request $request)
    {
        $query = $this->buildSearchQuery($request);
        
        // Pagination
        $perPage = $request->get('per_page', 12);
        $destinations = $query->paginate($perPage);
        
        // Transform the results
        $results = $destinations->getCollection()->map(function($destination) {
            return [
                'id' => $destination->id,
                'name' => $destination->name,
                'slug' => $destination->slug,
                'city' => $destination->city,
                'province' => $destination->province,
                'price' => $destination->price,
                'rating' => $destination->rating,
                'reviews_count' => $destination->reviews_count ?? 0,
                'short_description' => $destination->short_description,
                'primary_image' => $destination->primary_image ? asset('storage/' . $destination->primary_image) : asset('images/placeholder.jpg'),
                'is_featured' => (bool) $destination->is_featured,
                'url' => route('destinations.show', $destination->slug),
            ];
        });
        
        return response()->json([
            'data' => $results,
            'links' => (string) $destinations->withQueryString()->links(),
            'total' => $destinations->total(),
            'current_page' => $destinations->currentPage(),
            'last_page' => $destinations->lastPage(),
        ]);
    }

    /**
     * Get filter options for the search
     */
    public function filterOptions()
    {
        $cities = Destination::select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter()
            ->values();

        $provinces = Destination::select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province')
            ->filter()
            ->values();

        $priceRange = [
            'min' => (int) Destination::min('price') ?: 0,
            'max' => (int) ceil(Destination::max('price') / 100000) * 100000,
        ];

        return response()->json([
            'cities' => $cities,
            'provinces' => $provinces,
            'price_range' => $priceRange,
        ]);
    }

    /**
     * Handle autocomplete search
     */
    public function autocomplete(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('query');
        
        $destinations = Destination::query()
            ->select('id', 'name', 'slug', 'city', 'province', 'primary_image')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->orWhere('province', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'location' => $item->city . ', ' . $item->province,
                    'slug' => $item->slug,
                    'image' => $item->primary_image ? asset('storage/' . $item->primary_image) : asset('images/placeholder.jpg'),
                    'url' => route('destinations.show', $item->slug),
                ];
            });

        return response()->json($destinations);
    }

    /**
     * Build the search query based on request parameters
     */
    protected function buildSearchQuery(Request $request)
    {
        $query = Destination::query()
            ->with(['facilities', 'reviews'])
            ->withCount('reviews as reviews_count')
            ->withAvg('reviews as rating', 'rating')
            ->active();

        // Search query
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%")
                  ->orWhere('province', 'like', "%{$searchTerm}%");
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

        // Sorting
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

        return $query;
    }
}
