<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Admin API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/

Route::post('admin/login',  [\App\Http\Controllers\Auth\LoginController::class, 'adminlogin'])->name('admin.login');
Route::get('notfound', [UserController::class, 'notFound'])->name('admin.notfound');
Route::post('logout',  [LoginController::class, 'logout'])->name('customer.logout')->middleware('auth:sanctum');

Route::middleware('auth:web')->group(function () {
    Route::post('admins', [UserController::class, 'store'])->name('admins.create');
    Route::get('admins', [UserController::class, 'index'])->name('admins.all');
//    Route::post('logout',  [LoginController::class, 'logout'])->name('admin.logout');
    Route::get('admins/{admin}', [UserController::class, 'show'])->name('admins.admin')
        ->missing(function (Request $request) {
            return Redirect::route('admin.notfound');
        });
    Route::put('admins/{admin}', [UserController::class, 'update'])->name('admins.update')
        ->missing(function (Request $request) {
            return Redirect::route('admin.notfound');
        });
    Route::delete('admins/{admin}', [UserController::class, 'destroy'])->name('admins.delete')
        ->missing(function (Request $request) {
            return Redirect::route('admin.notfound');
        });
});



/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Customers API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/


Route::post('customer/login',  [LoginController::class, 'customerlogin'])->name('customer.login');
Route::get('notfound', [CustomerController::class, 'notFound'])->name('customer.notfound');

Route::middleware('auth:customer')->group(function () {
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.create');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.all');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.customer')
        ->missing(function (Request $request) {
            return Redirect::route('customer.notfound');
        });
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')
        ->missing(function (Request $request) {
            return Redirect::route('customer.notfound');
        });
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.delete')
        ->missing(function (Request $request) {
            return Redirect::route('customer.notfound');
        });
});

/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Products API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/

Route::get('notfound', [ProductController::class, 'notFound'])->name('product.notfound');
Route::post('search', [ProductController::class, 'search'])->name('product.search');
Route::get('products', [ProductController::class, 'index'])->name('product.all');
Route::get('products/{product}', [ProductController::class, 'show'])->name('product.category')
    ->missing(function (Request $request) {
        return Redirect::route('product.notfound');
    });
Route::middleware('auth:web')->group(function () {
    Route::post('products', [ProductController::class,'store'])->name('product.create');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('product.update')
        ->missing(function (Request $request) {
            return Redirect::route('product.notfound');
        });
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('product.delete')
        ->missing(function (Request $request) {
            return Redirect::route('product.notfound');
        });
});



/*
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Products API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|----------------------------------------------------------------------------------------------------------------------------------------------------
*/

Route::get('shopping_cart/{product}', [CartController::class, 'getTempCart']);
Route::get('shopping_cart/{product}/{quantity}', [CartController::class, 'setTempCart']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
