<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');

});

Route::group(['prefix' => 'tasks'], function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('show/{id}', 'show');
        Route::post('update/{task}', 'update');
        Route::post('update-status/{task}', 'updateStatus');
        Route::post('delete/{task}', 'destroy');
    });
});



