<?php

use App\Http\Controllers\Api\v1\Company\AuthController;
use App\Http\Controllers\Api\v1\Company\CompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 *  API Company Routes
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider and all of them will
 * be assigned to the "api" middleware group. Make something great!
 */

Route::group(['prefix' => '/auth'], function () {

    Route::post('/login', [AuthController::class, 'login']);


});
//  "message": "Unauthenticated." 401 error message when the user is not authenticated or authorized to access the resource.
Route::group(['middleware' => 'auth:organization'], function () {

    //check-qr-code
    Route::post('/check-qr-code', [CompanyController::class, 'checkQrCode']);
    //check-qr-code for exited
    Route::post('/check-qr-code-exited', [CompanyController::class, 'checkQrCodeExited']);
    //logout
    Route::post('/logout', [AuthController::class, 'logout']);

    //Route change order status
    Route::post('/order/{id}', [CompanyController::class, 'changeOrderStatus']);


});


