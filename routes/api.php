<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleCommentController;
use App\Http\Controllers\Api\ArticleController;
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
    Route::post('login', [ AuthController::class, 'login' ])->name('loginApi');

    Route::post('register', [ AuthController::class, 'register' ])->name('registerApi');

});

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'middleware'=> ['jwt.verify']

], function () {
    Route::post('logout', [ AuthController::class, 'logout' ])->name('logout');

    Route::post('me', [ AuthController::class, 'me' ])->name('me');

    Route::post('refresh', [ AuthController::class, 'refresh' ])->name('refresh');
});
Route::group(['middleware' => 'jwt.verify'], function () {

Route::apiresources([
    'articleResource'  => ArticleController::class,
    'articles.comments'=> ArticleCommentController::class
]);

    Route::get('allComments/{article_id}', [ ArticleCommentController::class, 'showAll' ])->name('allComments');
});


