<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Website\{CategoryController,
    CityController,
    CountryController,
    EventController,
    SliderController,
    StateController,
};
use App\Http\Controllers\Api\v1\User\ContactController;


/*
 *  API Routes
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider and all of them will
 *  be assigned to the "api" middleware group. Make something great!
 *
 */
// Required login group


Route::get('test', function () {
    return response()->json(['message' => 'Hello World!']);
});
    // Country Group
    Route::apiResource('countries', CountryController::class)->only(["index", "show"]);

    // State Group
    Route::apiResource('countries.states', StateController::class)->only(["index", "show"]);

    // City Group
    Route::apiResource('countries.states.cities', CityController::class)->only(["index", "show"]);

    //Category Event
    Route::apiResource('categories', CategoryController::class)->only(["index", "show"]);

    //Event Group
    Route::apiResource('events', EventController::class)->only(["index", "show"]);

    //Slider
    Route::get('sliders', [SliderController::class, 'index']);

    Route::get('terms-and-conditions', [ContactController::class, 'termsAndConditions']);



