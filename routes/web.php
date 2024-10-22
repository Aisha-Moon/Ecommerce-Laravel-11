<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Frontend\BrandController;
use App\Http\Controllers\Frontend\PolicyController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Backend\Api\InvoiceController;
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

    Route::get('/customer_profile', [CustomerProfileController::class, 'ReadProfile']);
    Route::post('/customer_profile', [CustomerProfileController::class, 'CreateProfile']);
    Route::post('/CreateProductReview', [ReviewController::class, 'CreateProductReview']);

});

//payment
Route::post("/paymentSuccess",[InvoiceController::class,'PaymentSuccess']);
Route::post("/paymentCancel",[InvoiceController::class,'PaymentCancel']);
Route::post("/paymentFail",[InvoiceController::class,'PaymentFail']);

Route::get("/",[HomeController::class,'home']);
Route::get('/login', [App\Http\Controllers\Frontend\UserController::class, 'LoginPage']);
Route::get('/verify', [App\Http\Controllers\Frontend\UserController::class, 'VerifyPage']);
Route::get('/logout',[UserController::class,'UserLogout']);


Route::get('/by-category', [CategoryController::class, 'ByCategoryPage']);
Route::get('/by-brands', [BrandController::class, 'ByBrandPage']);
Route::get('/policy', [PolicyController::class, 'PolicyPage']);
Route::get('/details', [ProductController::class, 'Details']);


Route::get('/wish', [ProductController::class, 'WishList']);
Route::get('/cart', [ProductController::class, 'CartListPage']);
Route::get('/profile', [ProfileController::class, 'ProfilePage']);




Route::get('/ListProductByRemark/{remark}', [ProductController::class, 'ListProductByRemark']);
Route::get('/ListProductByCategory/{id}', [CategoryController::class, 'ListProductByCategory']);
Route::get('/ListProductByBrand/{id}', [BrandController::class, 'ListProductByBrand']);
Route::get('/ListReviewByProduct/{product_id}', [ProductController::class, 'ListReviewByProduct']);

Route::get("/PolicyByType/{type}",[PolicyController::class,'PolicyByType']);


Route::get('/token',function(){
    dd(Cookie::get('token'));
});

Route::get('/test-token', function (Request $request) {
    $token = $request->cookie('token');
    return response()->json(['token' => $token]);
});
