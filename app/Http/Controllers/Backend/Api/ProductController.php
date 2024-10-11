<?php


namespace App\Http\Controllers\Backend\Api;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper; 
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ResponseHelper::Out('Products retrieved successfully.', $products, 200);
    }

  
        

        public function store(Request $request)
        {
            // Define validation rules
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'short_desc' => 'nullable|string|max:500',
                'price' => 'required|numeric',
                'discount' => 'nullable|boolean',
                'discount_price' => 'nullable|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'nullable|boolean',
                'star' => 'nullable|numeric|min:0|max:5',
                'remark' => 'nullable|in:popular,new,top,special,trending,regular',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
            ]);
        
          
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        
            $imagePath = null;
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $filename = $img->getClientOriginalName();
                $imagePath = "uploads/products/$filename";
                $img->move(public_path('uploads/products'), $filename);
            }
        
            $product = new Product();
            $product->title = $request->input('title');
            $product->short_desc = $request->input('short_desc');
            $product->price = $request->input('price');
            $product->discount = $request->input('discount');
            $product->discount_price = $request->input('discount_price');
            $product->image = $imagePath;
            $product->stock = $request->input('stock');
            $product->star = $request->input('star');
            $product->remark = $request->input('remark', 'regular');
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');
            $product->save();
        
            return response()->json(['message' => 'Product created successfully', 'data' => $product], 201);
        }
          
    
    


    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return ResponseHelper::Out('Product retrieved successfully.', $product, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_desc' => 'nullable|string|max:500',
            'price' => 'required|numeric',
            'discount' => 'nullable|boolean',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|boolean',
            'star' => 'nullable|numeric|min:0|max:5',
            'remark' => 'nullable|in:popular,new,top,special,trending,regular',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $product = Product::findOrFail($id);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $filename = $img->getClientOriginalName();
            $imagePath = "uploads/products/$filename";
            $img->move(public_path('uploads/products'), $filename);
    
            // Delete the old image if it exists
            if ($product->image) {
                $existingImagePath = public_path($product->image);
                if (file_exists($existingImagePath)) {
                    try {
                        unlink($existingImagePath);
                    } catch (Exception $e) {
                        return ResponseHelper::Out('Error deleting old image: ' . $e->getMessage(), null, 500);
                    }
                }
            }
    
           
            $product->image = $imagePath;
        }
    
        // Update other fields
        $product->title = $request->input('title');
        $product->short_desc = $request->input('short_desc');
        $product->price = $request->input('price');
        $product->discount = $request->input('discount');
        $product->discount_price = $request->input('discount_price');
        $product->stock = $request->input('stock'); 
        $product->star = $request->input('star');
        $product->remark = $request->input('remark', 'regular'); 
        $product->category_id = $request->input('category_id');
        $product->brand_id = $request->input('brand_id');
    
        // Save the updated product
        $product->save();
    
        return ResponseHelper::Out('Product updated successfully.', $product, 200);
    }
    

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            $imagePath = public_path($product->image);
            if (file_exists($imagePath)) {
                try {
                    unlink($imagePath);
                } catch (Exception $e) {
                    return ResponseHelper::Out('Error deleting image: ' . $e->getMessage(), null, 500);
                }
            }
        }

        $product->delete();
        return ResponseHelper::Out('Product deleted successfully.', null, 200);
    }
}
