<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopingController;
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



Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('facebook.login');
Route::get('/auth/facebook/callback', [SocialController::class,'callback']);

Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialController::class,'callback']);

Route::get('auth_redirect/{driver}', [SocialController::class,'redirect'])->name('github.login');
Route::get('/auth/github/callback', [SocialController::class,'callback']);

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Cart API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/shopping', [ShopingController::class, 'index'])->name('shopping');
Route::get('productCart', [ShopingController::class, 'tempCart']);
Route::get('shopping_cart', [ShopingController::class, 'getTempCart']);

//must applay auth middleware
Route::middleware('auth')->group(function () {
    Route::get('shopping_cart/{product}/{quantity}', [ShopingController::class, 'setTempCart']);
    Route::get('cart', [ShopingController::class, 'cart'])->name('cart');
    Route::get('cart/{product}/{quantity}', [ShopingController::class, 'addProduct']);
    Route::get('cart/{product}', [ShopingController::class, 'deleteProduct'])->name('delete_cart_product');
    Route::get('delete_cart', [ShopingController::class, 'deleteCart'])->name('delete_cart');


/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Cart API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/


    Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');


});
