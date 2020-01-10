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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::prefix('users')->group(function () {
    Route::get('/{user}', 'UserController@show');
    Route::group(['middleware' => ['auth:api', 'isAdminOrSelf']], function () {
        Route::put('/{user}', 'UserController@update');
    });
});

Route::prefix("bid")->group(function () {
    Route::get("/{ad}", 'BidController@index');
    // Route::get("/{ad}", 'BidController@show');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post("/", 'BidController@store');
        Route::delete("/{bid}", 'BidController@destroy');
    });
});

Route::prefix("ads")->group(function () {
    Route::get("/", 'AdsController@index');
    Route::post("/search", 'AdsController@search');
    // Route::get("/{ad}", 'AdsController@show');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post("/", 'AdsController@store');
        Route::put("/{ad}", 'AdsController@update');
        Route::delete("/{ad}", 'AdsController@destroy');
    });
});

Route::prefix("category")->group(function () {
    Route::get('/', 'CategoryController@index');
});

Route::prefix('file')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post("/", 'FileController@store');
    });
    Route::group(['middleware' => ['auth:api, isAdmin']], function () {
        Route::delete("/{slug}", 'FileController@delete');
    });
});

Route::prefix("feature")->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post("/{ad}", 'FeatureController@store');
    });
});
