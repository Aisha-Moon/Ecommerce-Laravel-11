<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Api\BrandController;
use App\Http\Controllers\Backend\Api\ProductController;
use App\Http\Controllers\Backend\Api\CategoryController;
use App\Http\Controllers\Backend\Api\ProductReviewController;
use App\Http\Controllers\Backend\Api\ProductDetailsController;

Route::apiResource('brands', BrandController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('productDetails', ProductDetailsController::class);
Route::apiResource('productReviews', ProductReviewController::class);

