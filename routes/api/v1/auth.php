<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\SocialiteController;
use App\Http\Controllers\Api\v1\Auth\RestPasswordController;
use Illuminate\Support\Facades\Route;

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
foreach (['user', 'figure', 'company'] as $role) {
    Route::prefix($role)->group(function () use ($role) {
        // Register user.
        Route::post("/register", [AuthController::class, "register"])->name("$role.register")->middleware('throttle:6,1');
        // Login and return generated token for user.
        Route::post("/login", [AuthController::class, "login"])->name("$role.login")->middleware('throttle:6,1');


        // Login with Google
        Route::get("/google/redirect", [SocialiteController::class, "redirectToGoogle"])->name("$role.google_redirect");
        Route::get("/google/callback", [SocialiteController::class, "handleGoogleCallback"])->name("$role.google_callback");
        // Login with Facebook
        Route::get("/facebook/redirect", [SocialiteController::class, "redirectToFacebook"])->name("$role.facebook_redirect");
        Route::get("/facebook/callback", [SocialiteController::class, "handleFacebookCallback"])->name("$role.facebook_callback");

        // Get social profile
        Route::post("/social/profile", [SocialiteController::class, "getUserProfile"])->name("$role.social_profile");

        //verify phone
        Route::post("/verify-phone", [AuthController::class, "verifyPhone"])->name("$role.verify_phone");

        // Check the Email for user.
        Route::post("/check-email", [RestPasswordController::class, "checkEmail"])->name("$role.check_email");
        // Verify email
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name("verification.verify");

        // Reset password.
        Route::middleware(['throttle:6,1'])->group(function () use ($role) {
            Route::post('reset/check_code', [RestPasswordController::class, "checkCode"])->name("$role.check_code");
            Route::post('reset/reset_password', [RestPasswordController::class, "resetPassword"])->name("$role.reset_password");
        });

        Route::group(['middleware' => ['auth:sanctum', 'verified']], function () use ($role) {
            // Logout user.

            Route::post("/logout", [AuthController::class, "logout"])->name("$role.logout");

            // Edit user profile.
            Route::put("/edit-profile", [AuthController::class, "editProfile"])->name("$role.edit_profile");

            // Delete user profile.
            Route::delete("/delete-profile", [AuthController::class, "deleteProfile"])->name("$role.delete_profile");

            // get access token
            //  Route::get('/access-token', [AuthController::class, 'getAccessToken'])->name("$role.access_token");

            // get my profile
            Route::get('/profile', [AuthController::class, 'getProfile'])->name("$role.profile");
        });
    });
}
