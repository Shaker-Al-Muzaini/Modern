<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProdectController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//users

Route::get('/',[UserController::class,'index'])->name('home');
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
