<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'namespace' => 'App\Http\Controllers\Api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [ AuthController::class, 'login' ])->name('login');
    Route::post('register', [ AuthController::class, 'register' ])->name('register');
    Route::middleware(['jwt.verify'])->group(function () {

//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
    });
});