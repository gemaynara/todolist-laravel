<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::get('refresh', 'refresh');
    });

    Route::middleware('jwt.auth')->group(function () {
        Route::get('/me', [AuthController::class, 'getUser']);

        Route::group(['prefix' => 'tasks'], function () {
            Route::controller(TaskController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('store', 'store');
                Route::get('show/{id}', 'show');
                Route::post('update/{task}', 'update');
                Route::delete('delete/{task}', 'destroy');
            });
        });

        Route::group(['prefix' => 'users'], function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('store', 'store');
                Route::post('update/{id}', 'update');
                Route::get('show/{id}', 'show');
                Route::delete('delete/{id}', 'destroy');
            });
        });
    });
});
