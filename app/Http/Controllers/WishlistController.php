<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get wishlisted destinations with primary images and pagination
            $wishlists = $user->wishlistedDestinations()
                ->with(['images' => function($query) {
                    $query->where('is_primary', true);
                }])
                ->orderBy('wishlists.created_at', 'desc')
                ->paginate(12);
            
            return view('pages.wishlist.index', compact('wishlists'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching wishlist: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat wishlist. Silakan coba lagi nanti.');
        }
    }
    
    /**
     * Add a destination to wishlist via API (by ID or route param).
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $destination = null)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk mengakses wishlist.',
                    'requiresLogin' => true
                ], 401);
            }

            $destinationId = $destination ?? $request->input('destination_id');
            if (!$destinationId || !Destination::where('id', $destinationId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi tidak ditemukan.'
                ], 404);
            }
            if ($user->hasInWishlist($destinationId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi sudah ada di wishlist Anda.'
                ], 422);
            }
            $wishlist = Wishlist::create([
                'user_id' => $user->id,
                'destination_id' => $destinationId
            ]);
            $wishlistCount = $user->wishlists()->count();
            return response()->json([
                'success' => true,
                'data' => [
                    'wishlist_item' => $wishlist->load('destination.images'),
                    'wishlist_count' => $wishlistCount
                ],
                'message' => 'Destinasi berhasil ditambahkan ke wishlist!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding to wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove a destination from wishlist via API (by ID or route param).
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $destinationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $destinationId = null)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk mengakses wishlist.',
                    'requiresLogin' => true
                ], 401);
            }

            $destId = $destinationId ?? $request->input('destination_id');
            if (!$destId || !Destination::where('id', $destId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi tidak ditemukan.'
                ], 404);
            }
            $deleted = $user->wishlists()->where('destination_id', $destId)->delete();
            if ($deleted === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di wishlist Anda.'
                ], 404);
            }
            $wishlistCount = $user->wishlists()->count();
            return response()->json([
                'success' => true,
                'data' => [
                    'destination_id' => $destId,
                    'wishlist_count' => $wishlistCount
                ],
                'message' => 'Destinasi berhasil dihapus dari wishlist!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing from wishlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Toggle a destination in the wishlist via API (by ID or route param).
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, $destination = null)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk mengakses wishlist.',
                    'requiresLogin' => true
                ], 401);
            }

            $destinationId = $destination ?? $request->input('destination_id');
            if (!$destinationId || !Destination::where('id', $destinationId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Destinasi tidak ditemukan.'
                ], 404);
            }
            $action = '';
            $existingItem = $user->wishlists()->where('destination_id', $destinationId)->first();
            if ($existingItem) {
                $existingItem->delete();
                $action = 'removed';
            } else {
                Wishlist::create([
                    'user_id' => $user->id,
                    'destination_id' => $destinationId
                ]);
                $action = 'added';
            }
            $wishlistCount = $user->wishlists()->count();
            return response()->json([
                'success' => true,
                'data' => [
                    'action' => $action,
                    'destination_id' => $destinationId,
                    'wishlist_count' => $wishlistCount,
                    'in_wishlist' => $action === 'added'
                ],
                'message' => $action === 'added' ? 'Destinasi berhasil ditambahkan ke wishlist!' : 'Destinasi berhasil dihapus dari wishlist!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling wishlist (API): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Check if a destination is in the user's wishlist via API.
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $destinationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request, $destinationId = null)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk mengakses wishlist.',
                    'requiresLogin' => true
                ], 401);
            }

            $destId = $destinationId ?? $request->input('destination_id');
            $inWishlist = $user->hasInWishlist($destId);
            return response()->json([
                'success' => true,
                'data' => [
                    'in_wishlist' => $inWishlist,
                    'destination_id' => $destId
                ],
                'message' => $inWishlist ? 'Destinasi ada di wishlist Anda.' : 'Destinasi tidak ada di wishlist.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking wishlist status (API): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status wishlist.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get user wishlist items as JSON.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWishlist()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk mengakses wishlist.',
                    'requiresLogin' => true
                ], 401);
            }

            $wishlists = $user->wishlistedDestinations()
                ->with(['images' => function($query) {
                    $query->where('is_primary', true);
                }])
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'wishlists' => $wishlists,
                    'count' => $wishlists->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching wishlist JSON: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data wishlist.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
