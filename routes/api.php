<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'] ,function() {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('menus/catering', 'MenuController@catering');

    Route::resource('orders', 'OrderController', ['except' => ['create', 'edit']])->middleware('jwt.auth');
    Route::resource('orders.menu-items', 'OrderMenuItemController', ['except' => ['create', 'edit']]);

    Route::get('payment/nonce', 'OrderPaymentController@generatePaymentToken');
    Route::post('orders/{id}/pay', 'OrderPaymentController@pay');
    Route::get('orders/{id}/invoice', 'OrderController@invoice');
});
