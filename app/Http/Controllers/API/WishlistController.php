<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Display a listing of the authenticated user's wishlist items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Get paginated wishlist items with eager loading
            $wishlistItems = $user->wishlistedDestinations()
                ->with(['images' => function($query) {
                    $query->where('is_primary', true);
                }])
                ->orderBy('wishlists.created_at', 'desc')
                ->paginate(12);
            
            return response()->json([
                'success' => true,
                'data' => $wishlistItems,
                'message' => 'Wishlist items retrieved successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching wishlist items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve wishlist items.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Add a destination to the user's wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'destination_id' => 'required|exists:destinations,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $destinationId = $request->input('destination_id');
            
            // Check if already in wishlist
            if ($user->hasInWishlist($destinationId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destination is already in your wishlist.'
                ], 422);
            }
            
            // Add to wishlist
            $wishlistItem = Wishlist::create([
                'user_id' => $user->id,
                'destination_id' => $destinationId
            ]);
            
            // Get the updated wishlist count
            $wishlistCount = $user->wishlists()->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'wishlist_item' => $wishlistItem->load('destination.images'),
                    'wishlist_count' => $wishlistCount
                ],
                'message' => 'Destination added to wishlist successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error adding to wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add destination to wishlist.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified wishlist item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $wishlistItem = Wishlist::with(['destination.images', 'user'])
                ->findOrFail($id);
                
            // Check if the authenticated user owns this wishlist item
            if (auth()->id() !== $wishlistItem->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this wishlist item.'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $wishlistItem,
                'message' => 'Wishlist item retrieved successfully.'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching wishlist item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve wishlist item.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified destination from the user's wishlist.
     *
     * @param  int  $destinationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($destinationId): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Find the wishlist item
            $wishlistItem = Wishlist::where('user_id', $user->id)
                ->where('destination_id', $destinationId)
                ->first();
                
            if (!$wishlistItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destination not found in your wishlist.'
                ], 404);
            }
            
            // Delete the wishlist item
            $wishlistItem->delete();
            
            // Get the updated wishlist count
            $wishlistCount = $user->wishlists()->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'destination_id' => $destinationId,
                    'wishlist_count' => $wishlistCount
                ],
                'message' => 'Destination removed from wishlist successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error removing from wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove destination from wishlist.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Toggle a destination in the user's wishlist.
     * Adds if not present, removes if already present.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'destination_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Check if destination exists
            $destinationId = $request->input('destination_id');
            if (!Destination::where('id', $destinationId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi tidak ditemukan.'
                ], 404);
            }
            
            $user = $request->user();
            $destinationId = $request->input('destination_id');
            
            // Check if already in wishlist
            $existingItem = Wishlist::where('user_id', $user->id)
                ->where('destination_id', $destinationId)
                ->first();
                
            if ($existingItem) {
                // Remove from wishlist
                $existingItem->delete();
                $action = 'removed';
            } else {
                // Add to wishlist
                $existingItem = Wishlist::create([
                    'user_id' => $user->id,
                    'destination_id' => $destinationId
                ]);
                $action = 'added';
            }
            
            // Get the updated wishlist count
            $wishlistCount = $user->wishlists()->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'action' => $action,
                    'destination_id' => $destinationId,
                    'wishlist_count' => $wishlistCount,
                    'in_wishlist' => $action === 'added'
                ],
                'message' => "Destination {$action} to wishlist successfully."
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling wishlist item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update wishlist.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Check if a destination is in the user's wishlist.
     *
     * @param  int  $destinationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function check($destinationId): JsonResponse
    {
        try {
            $user = auth()->user();
            $inWishlist = $user->hasInWishlist($destinationId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'in_wishlist' => $inWishlist,
                    'destination_id' => $destinationId
                ],
                'message' => $inWishlist 
                    ? 'Destination is in your wishlist.' 
                    : 'Destination is not in your wishlist.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking wishlist status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check wishlist status.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
