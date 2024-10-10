<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Api\BrandController;
use App\Http\Controllers\Backend\Api\CategoryController;

Route::apiResource('brands', BrandController::class);
Route::apiResource('categories', CategoryController::class);