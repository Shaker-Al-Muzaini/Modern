<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProdectController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//users

Route::get('/',[UserController::class,'index'])->name('home');
Route::get('/dashboard',[DashboardController::class,'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

//admin routes

Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('dashboard');
    Route::get('/Product',[ProdectController::class,'index'])
        ->name('product.index');
    Route::post('/Product',[ProdectController::class,'store'])
        ->name('product.store');
    Route::put('/products/update/{id}',[ProdectController::class,'update'])
        ->name('admin.products.update');
    Route::delete('/products/image/{id}',[ProdectController::class,'deleteImage'])
        ->name('admin.products.image.delete');
    Route::delete('/products/destory/{id}',[ProdectController::class,'destory'])
        ->name('admin.products.destory');

});

//end
//add to cart

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view','view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{product}','update')->name('cart.update');
    Route::delete('delete/{product}','delete')->name('cart.delete');
});

Route::prefix('products')->controller(ProductListController::class)->group(function ()  {
    Route::get('/','index')->name('products.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //chekcout
    Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
        Route::post('order','store')->name('checkout.store');
        Route::get('success','success')->name('checkout.success');
        Route::get('cancel','cancel')->name('checkout.cancel');
    }));

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
