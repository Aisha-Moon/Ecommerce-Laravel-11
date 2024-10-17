<?php

namespace App\Http\Controllers\Backend\Api;

use App\Models\ProductWish;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
   
    public function ProductWishList(Request $request){
        $user_id=$request->header('id');
        $data=ProductWish::where('customer_id',$user_id)->with('product')->get();
        return ResponseHelper::Out('success',$data,200);
    }

    public function CreateWishList(Request $request){
        
        $user_id=$request->header('id');
        $data=ProductWish::updateOrCreate(
            ['customer_id' => $user_id,'product_id'=>$request->product_id],
            ['customer_id' => $user_id,'product_id'=>$request->product_id],
        );
        return ResponseHelper::Out('success',$data,200);
    }


    public function RemoveWishList(Request $request)
{
    $user_id = $request->header('id');
    $product_id = $request->product_id;

    // Check if record exists
    $record = ProductWish::where(['customer_id' => $user_id, 'product_id' => $product_id])->first();
    
    if (!$record) {
        return ResponseHelper::Out('Record not found', null, 404);
    }

    // Proceed to delete
    $data = $record->delete();
    return ResponseHelper::Out('success', $data, 200);
}

    

}
