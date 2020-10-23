<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/register', 'ApiAuthController@register');
        Route::post('/login', 'ApiAuthController@login');

        Route::middleware('auth:api')->group(function () {
            Route::get('/user', 'ApiUserController@getLoggedInUser');
        });
    });
});

Route::name('subscriptions.store')->post('/subscriptions/store/{plan}', 'CreateSubscriptionController');
