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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','middleware' => ['cors', 'json.response']], function () {

    Route::post('/login', 'AuthController@login')->name('login.api');
    Route::post('/register','AuthController@register')->name('register.api');

    Route::resource('menu','MenuController');
    Route::resource('orders','OrdersController');
});
Route::middleware('auth:api')->group(function () {

    Route::post('/logout', 'AuthController@logout')->name('logout.api');
});
