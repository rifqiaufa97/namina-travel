<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;

use App\Models\Destination;

Route::get('/', function () {
    $destinations = Destination::where('is_active', true)
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
        
    return view('pages.home', compact('destinations'));
})->name('home');

// Static Pages
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [PageController::class, 'kontak'])->name('contact');
Route::post('/kontak', [PageController::class, 'submitContact'])->name('contact.submit');

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
});

// Destination Routes
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');

// Search Routes
Route::get('/cari', [\App\Http\Controllers\DestinationSearchController::class, 'index'])->name('search');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Booking Routes
    Route::get('/booking/{destination}', [\App\Http\Controllers\BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/booking/process', [\App\Http\Controllers\BookingController::class, 'processBooking'])->name('booking.process');
    
    // Payment Routes (Manual BCA)
    Route::get('/payment/{order}', [\App\Http\Controllers\PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment/{order}/upload-proof', [\App\Http\Controllers\PaymentController::class, 'uploadPaymentProof'])->name('payment.upload-proof');
    
    // Booking Success
    Route::get('/booking/success/{order}', [\App\Http\Controllers\BookingController::class, 'bookingSuccess'])->name('booking.success');
    
    // Wishlist Routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WishlistController::class, 'index'])->name('index');
        Route::get('/check/{destination}', [\App\Http\Controllers\WishlistController::class, 'check'])->name('check');
        Route::post('/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle');
        Route::post('/{destination}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle.item');
    });

    // Wishlist AJAX / API routes (Web Session)
    Route::prefix('api/wishlist')->name('api.wishlist.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WishlistController::class, 'getWishlist'])->name('get');
        Route::post('/', [\App\Http\Controllers\WishlistController::class, 'store'])->name('store');
        Route::post('/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('toggle');
        Route::post('/{destination}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('store.item');
        Route::delete('/{destination}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('destroy');
        Route::get('/check/{destination}', [\App\Http\Controllers\WishlistController::class, 'check'])->name('check');
    });
});

// Review Routes
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/profil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

// Admin Analytics Routes
// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::post('/analytics/filter', [\App\Http\Controllers\Admin\AnalyticsController::class, 'filter'])->name('admin.analytics.filter');
    
    // Admin Chat Routes
    Route::prefix('chat')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('admin.chat.index');
        Route::get('/messages/{user}', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('admin.chat.messages');
        Route::post('/send', [\App\Http\Controllers\Admin\ChatController::class, 'send'])->name('admin.chat.send');
        Route::get('/unread-count', [\App\Http\Controllers\Admin\ChatController::class, 'unreadCount'])->name('admin.chat.unread');
    });
});

// Pesanan Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan-saya', [PesananController::class, 'index'])->name('pesanan-saya');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Tour Packages
    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show')->whereNumber('order');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status')->whereNumber('order');
    Route::post('/orders/{order}/verify-payment', [\App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify-payment')->whereNumber('order');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice')->whereNumber('order');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['index', 'show']);
    
    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.update-status');
    
    // Travel Management
    Route::get('/travel-management', [\App\Http\Controllers\Admin\TravelManagementController::class, 'index'])->name('travel.management');
    
    // Route untuk menghapus gambar galeri destinasi
    Route::delete('/packages/{package}/images/{image}', [\App\Http\Controllers\Admin\PackageController::class, 'deleteImage'])
        ->name('packages.images.destroy')
        ->where(['package' => '[0-9]+', 'image' => '[0-9]+']);
});

// User Dashboard Redirect
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('pesanan-saya');
    })->name('dashboard');
});
