<?php

use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use App\Http\Controllers\Backend\Api\BrandController;
use App\Http\Controllers\Backend\Api\PolicyController;
use App\Http\Controllers\Backend\Api\InvoiceController;
use App\Http\Controllers\Backend\Api\ProductController;
use App\Http\Controllers\Backend\Api\CategoryController;
use App\Http\Controllers\Backend\Api\WishlistController;
use App\Http\Controllers\Backend\Api\ProductCartController;
use App\Http\Controllers\Backend\Api\ProductReviewController;
use App\Http\Controllers\Backend\Api\ProductSliderController;
use App\Http\Controllers\Backend\Api\ProductDetailsController;


Route::apiResource('brands', BrandController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('productDetails', ProductDetailsController::class);
Route::apiResource('productReviews', ProductReviewController::class)->middleware(TokenVerifyMiddleware::class);
Route::apiResource('productSliders', ProductSliderController::class);
Route::apiResource('policies', PolicyController::class);


Route::get('/UserLogin/{UserEmail}', [UserController::class, 'UserLogin']);
Route::get('/VerifyLogin/{UserEmail}/{OTP}', [UserController::class, 'VerifyLogin']);
Route::get('/logout',[UserController::class,'UserLogout']);


Route::middleware([TokenVerifyMiddleware::class])->group(function () {
// Product Wish
Route::get('/ProductWishList', [WishlistController::class, 'ProductWishList']);
Route::get('/CreateWishList/{product_id}', [WishlistController::class, 'CreateWishList']);
Route::get('/RemoveWishList/{product_id}', [WishlistController::class, 'RemoveWishList']);
 // Product Cart

 Route::post('/CreateCartList', [ProductCartController::class, 'CreateCartList']);
 Route::get('/CartList', [ProductCartController::class, 'CartList']);
 Route::get('/DeleteCartList/{product_id}', [ProductCartController::class, 'DeleteCartList']);
 
 });



// Invoice and payment\
Route::middleware([TokenVerifyMiddleware::class])->group(function () {

Route::get("/InvoiceCreate",[InvoiceController::class,'InvoiceCreate']);
Route::get("/InvoiceList",[InvoiceController::class,'InvoiceList']);
Route::get("/InvoiceProductList/{invoice_id}",[InvoiceController::class,'InvoiceProductList']);

});
Route::post("/PaymentIPN",[InvoiceController::class,'PaymentIPN']);
