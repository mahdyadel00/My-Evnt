<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Api\v1\Auth\{AuthController, RestPasswordController, SocialiteController};
use App\Http\Controllers\Api\v1\User\{
    CategoryController,
    CheckoutController,
    ContactController,
    EventController,
    TicketController,
    OrderController
};



/*
 * API Routes
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider and all of them will
 * be assigned to the "api" middleware group. Make something great!
 */

Route::group(['prefix' => '/auth'], function () {

    // تشخيص إعدادات Twilio على السيرفر (لا يعرض قيم سرية)
    Route::get('/twilio-config-check', function () {
        return response()->json([
            'twilio_sid_set'     => !empty(Config::get('services.twilio.sid')),
            'twilio_token_set'  => !empty(Config::get('services.twilio.token')),
            'otp_channel'       => Config::get('services.twilio.otp_channel', 'sms'),
            'sms_from_set'      => !empty(Config::get('services.twilio.sms_from')),
            'whatsapp_from_set' => !empty(Config::get('services.twilio.whatsapp_from')),
            'hint'              => 'If any is false or wrong, fix .env on server and run: php artisan config:clear',
        ]);
    });

    Route::post("/register", [AuthController::class, "register"]);
    //verify phone
    Route::post("/verify-phone", [AuthController::class, "verifyPhone"]);

    Route::post('/login', [AuthController::class, 'login']);

    // Login with socialite
    Route::post("/login-socialite", [SocialiteController::class, "loginSocialite"]);


    //Route Reset Password
    Route::post('forget-password', [RestPasswordController::class, 'forgetPassword']);
    Route::post('check-reset-code', [RestPasswordController::class, 'checkResetCode']);
    Route::post('reset-password', [RestPasswordController::class, 'resetPassword']);


    //Route reset password
    Route::post('reset-password', [RestPasswordController::class, 'resetPassword']);
});
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('edit-profile', [AuthController::class, 'editProfile']);
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);

    // Get social profile
    Route::post("/social/profile", [SocialiteController::class, "getUserProfile"])->name("user.social_profile");



    //category interested
    Route::post('category-interested', [AuthController::class, 'categoryInterested']);

    //Route Logout
    Route::post('logout', [AuthController::class, 'logout']);

    //Route change password
    Route::post('change-password', [AuthController::class, 'changePassword']);

    //Route delete user
    Route::delete('delete-profile', [AuthController::class, 'deleteProfile']);

    //Route Category
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('category/{id}', [CategoryController::class, 'show']);

    //Route Event
    Route::get('events', [EventController::class, 'index']);
    Route::get('event/{id}', [EventController::class, 'show']);

    //Route favorite
    Route::get('favorites', [EventController::class, 'favorites']);
    Route::post('favorite/{id}', [EventController::class, 'favorite']);

    //Contactus
    Route::post('contactus', [ContactController::class, 'contactus']);

    //create order
    Route::post('create-order', [OrderController::class, 'createOrder']);
    //my orders
    Route::get('my-orders', [OrderController::class, 'myOrders']);
    //my order
    Route::get('my-order/{id}', [OrderController::class, 'myOrder']);
    //checkout order
    Route::post('checkout-order', [CheckoutController::class, 'checkoutOrder']);
    Route::post('/payment/callback', [CheckoutController::class, 'updateOrderStatus']);

    //my tickets
    Route::get('my-tickets', [TicketController::class, 'myTickets']);
    //my ticket
    Route::get('my-ticket/{id}', [TicketController::class, 'myTicket']);

    //notifications
    Route::get('notifications', [AuthController::class, 'notifications']);
    Route::post('notification-read/{id}', [AuthController::class, 'notificationRead']);
    Route::get('notification-read-all', [AuthController::class, 'notificationReadAll']);
    //delete notification
    Route::delete('notification-delete/{id}', [AuthController::class, 'notificationDelete']);
    //delete all notification
    Route::delete('notification-delete-all', [AuthController::class, 'notificationDeleteAll']);

    //terms and conditions
    Route::get('terms-and-conditions', [ContactController::class, 'termsAndConditions']);
});
