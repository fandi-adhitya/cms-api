<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

Route::prefix("v1")->group(function () {
  Route::post('auth', [\App\Http\Controllers\Api\Auth\AuthController::class, 'auth']);

  Route::middleware('auth:api')->group(function () {
  
    Route::group(['middleware' => ['role:admin']], function () {
      Route::apiResource('user', \App\Http\Controllers\Api\User\UserController::class);
    });

    Route::group(['middleware' => ['role:admin|editor|user']], function () {
      Route::apiResource('post', \App\Http\Controllers\Api\Post\PostController::class);
    });
  });
});
