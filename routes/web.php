<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FrontendController;

Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [FrontendController::class, 'dashboardPage'])->name('web.admin.dashboard');
    Route::get('/product', [FrontendController::class, 'productPage'])->name('web.admin.product');
    Route::get('/category', [FrontendController::class, 'categoryPage'])->name('web.admin.category');
    Route::get('/brand', [FrontendController::class, 'brandPage'])->name('web.admin.brand');
});
