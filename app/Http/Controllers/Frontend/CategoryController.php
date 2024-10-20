<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function ByCategoryPage(){
        return view('frontend.pages.product_by_category');
    }

    public function ListProductByCategory(Request $request){
        $data=Product::where('category_id',$request->id)->with('brand','category')->get();
        return ResponseHelper::Out('success',$data,200);
    }
}
