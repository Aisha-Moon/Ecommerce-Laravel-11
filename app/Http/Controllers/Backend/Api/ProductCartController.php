<?php

namespace App\Http\Controllers\Backend\Api;

use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductWish;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class ProductCartController extends Controller
{

    public function CartList(Request $request){
        $user_id=$request->header('id');
        $data=ProductCart::where('customer_id',$user_id)->with('product')->get();
        return ResponseHelper::Out('success',$data,200);
    }
   
    public function CreateCartList(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $color = $request->input('color');
        $size = $request->input('size');
        $quantity = $request->input('quantity');
    
        $productDetails = Product::where('id', $product_id)->first();
    
        if (!$productDetails) {
            return ResponseHelper::Out('Product not found', null, 404);
        }
    
        $unitPrice = $productDetails->discount == 1 ? $productDetails->discount_price : $productDetails->price;
    
        $totalPrice = $quantity * $unitPrice;
    
        $product = ProductCart::updateOrCreate(
            ['customer_id' => $user_id, 'product_id' => $product_id],
            [
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
                'price' => $totalPrice,
                'customer_id' => $user_id,
                'product_id' => $product_id
            ]
        );
    
        $cartWithRelations = ProductCart::with(['product', 'customerProfile'])
            ->where('id', $product->id) 
            ->first();
    
        return ResponseHelper::Out('success', $cartWithRelations, 200);
    }
    public function DeleteCartList(Request $request,$product_id){
        $user_id = $request->header('id');
        $cart=ProductCart::where('customer_id',$user_id)->where('product_id',$product_id)->delete();
       
        return ResponseHelper::Out('success', $cart, 200);
 


    }
    
    

}
