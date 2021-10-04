<?php

use App\Http\Controllers\CurrentUserArticlesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewArticle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyRegisterController;
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

Route::get('/', [HomeController::class, 'view'])->name('index');

Route::get('/my-register', [MyRegisterController::class, 'view'])->name('myRegister');

Route::post('/my-register-post', [MyRegisterController::class, 'post'])->name('registerPost');

Auth::routes();

Route::get('/home', [CurrentUserArticlesController::class, 'index'])->name('home');

Route::get('/new-article', [NewArticle::class, 'view'])->name('newArticle');

Route::post('/new-article-post', [NewArticle::class, 'post'])->name('newArticlePost');

