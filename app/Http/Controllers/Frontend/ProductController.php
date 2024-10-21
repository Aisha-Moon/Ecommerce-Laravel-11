<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Policy;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function WishList()
    {
        return view('frontend.pages.wish-list-page');
    }
    public function CartListPage()
    {
        return view('frontend.pages.cart-list-page');
    }

public function Details()
    {
        return view('frontend.pages.details_page');
    }

    public function ListProductByRemark(Request $request){
     $data=Product::where('remark',$request->remark)->with('brand','category')->get();
     return ResponseHelper::Out('success',$data,200);
 }
 public function ListReviewByProduct(Request $request){
     $data=ProductReview::where('product_id',$request->product_id)
         ->with(['customerProfile'=>function($query){
             $query->select('id','cus_name');
         }])->get();
     return ResponseHelper::Out('success',$data,200);
 }
}