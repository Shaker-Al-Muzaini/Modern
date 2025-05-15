<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProdectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

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

});

//end

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
