<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Frontend\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Location API Routes (Countries & Cities)
|--------------------------------------------------------------------------
*/
Route::prefix('locations')->group(function () {
    Route::get('countries', [LocationController::class, 'countries'])->name('api.countries');
    Route::get('countries/{country}/cities', [LocationController::class, 'cities'])->name('api.cities');
    Route::get('cities', [LocationController::class, 'allCities'])->name('api.all-cities');
    Route::get('cities/search/{query}', [LocationController::class, 'searchCities'])->name('api.search-cities');
    Route::get('detect', [LocationController::class, 'detectLocation'])->name('api.detect-location');
    Route::post('update', [LocationController::class, 'updateLocation'])->name('api.update-location');
});

/*
|--------------------------------------------------------------------------
| Guest Survey API Route
|--------------------------------------------------------------------------
*/
Route::post('guest-survey/store', [EventController::class, 'storeGuestSurvey'])->name('api.guest-survey.store');
