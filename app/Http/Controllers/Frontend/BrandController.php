<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function ByBrandPage(){
        return view('frontend.pages.product_by_brand');
    }

    public function ListProductByBrand(Request $request){
        $data=Product::where('brand_id',$request->id)->with('brand','category')->get();
        return ResponseHelper::Out('success',$data,200);
    }
}
