<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TravelManagementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Travel Management Routes
// Travel Management Routes
Route::prefix('travel')->group(function () {
    Route::post('/itinerary/generate', [TravelManagementController::class, 'generateItinerary']);
    Route::get('/checklist', [TravelManagementController::class, 'getTravelChecklist']);
});

// Destination Search Routes
Route::prefix('search')->group(function () {
    Route::get('/autocomplete', [\App\Http\Controllers\DestinationSearchController::class, 'autocomplete']);
    Route::get('/filter-options', [\App\Http\Controllers\DestinationSearchController::class, 'filterOptions']);
    Route::get('/', [\App\Http\Controllers\DestinationSearchController::class, 'search']);
});

