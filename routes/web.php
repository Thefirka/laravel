<?php

use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ ArticleController::class, 'allArticles' ])->name('home');

Route::get('/my-register', [ UserController::class, 'registerNewUser' ])->name('myRegister');

Route::post('/my-register-post', [ UserController::class, 'registerPost' ])->name('registerPost');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/new-article', [ ArticleController::class, 'newArticle' ] )->name('newArticle');

    Route::post('/new-article', [ ArticleController::class, 'createArticle' ]);

    Route::post('/loadArticle/{slug}', [ ArticleController::class, 'loadArticle' ])->name('loadArticle');

    Route::post('/deleteArticle/{slug}', [ ArticleController::class, 'deleteArticle' ])->name('deleteArticle');

    Route::get('/articles', [ ArticleController::class, 'allCurrentUserArticles' ])->name('articles');
});

Route::get('/article/{slug}', [ ArticleController::class, 'showArticle' ])->name('article');

Route::post('');
