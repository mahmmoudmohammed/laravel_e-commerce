<?php

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/php', function () {
    return phpinfo();
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('facebook.login');
Route::get('/auth/facebook/callback', [SocialController::class,'callback']);

Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialController::class,'callback']);

Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('github.login');
Route::get('/auth/github/callback', [SocialController::class,'callback']);
