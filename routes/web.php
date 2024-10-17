<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Backend\Api\PolicyController;
use App\Http\Controllers\Backend\Api\WishlistController;
use App\Http\Controllers\Backend\Api\CustomerProfileController;

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [FrontendController::class, 'dashboardPage'])->name('web.admin.dashboard');
    Route::get('/product', [FrontendController::class, 'productPage'])->name('web.admin.product');
    Route::get('/category', [FrontendController::class, 'categoryPage'])->name('web.admin.category');
    Route::get('/brand', [FrontendController::class, 'brandPage'])->name('web.admin.brand');
});

// User Auth


//customer profile 

Route::middleware([TokenVerifyMiddleware::class])->group(function () {

    Route::get('/profile', [CustomerProfileController::class, 'ReadProfile']);
    Route::post('/profile', [CustomerProfileController::class, 'CreateProfile']);
    Route::put('/profile', [CustomerProfileController::class, 'UpdateProfile']);
    Route::delete('/profile', [CustomerProfileController::class, 'DeleteProfile']);

});

// Product Wish
