<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use App\Models\Product;
use App\Helper\ResponseHelper; 
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
class ProductController extends Controller
{
  

 
        public function index(Request $request)
        {
            // Initialize the query
            $query = Product::with(['brand', 'category', 'details']);
        
            // Apply category filter if category_id is present
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }
        
            // Apply brand filter if brand_id is present
            if ($request->has('brand_id')) {
                $query->where('brand_id', $request->brand_id);
            }
        
            // Apply remark filter if remark is present
            if ($request->has('remark')) {
                $query->where('remark', $request->remark);
            }
        
            // Execute the query and get the products
            $products = $query->get();
        
            return ResponseHelper::Out('Products retrieved successfully.', $products, 200);
        }
    
    
    
    
    

    public function store(StoreProductRequest $request) 
    {
        try {
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

            return ResponseHelper::Out('Product created successfully', $product, 201);
        } catch (Exception $e) {
            return ResponseHelper::Out('Failed to create product: ' . $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['brand', 'category','details'])->findOrFail($id);
            return ResponseHelper::Out('Product retrieved successfully.', $product, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
        } catch (Exception $e) {
            return ResponseHelper::Out('An error occurred while retrieving the product.', null, 500);
        }
    }
    

    public function update(StoreProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $filename = $img->getClientOriginalName();
                $imagePath = "uploads/products/$filename";
                $img->move(public_path('uploads/products'), $filename);

                // Delete the old image if it exists
                if ($product->image) {
                    $existingImagePath = public_path($product->image);
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
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
            $product->remark = $request->input('remark'); 
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');

            // Save the updated product
            $product->save();

            return ResponseHelper::Out('Product updated successfully.', $product, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
        } catch (Exception $e) {
            return ResponseHelper::Out('Failed to update product: ' . $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);
    
            $detail = $product->details; 
    
            if ($detail) {
                // Loop through each image field and delete if it exists
                $imageFields = ['img1', 'img2', 'img3', 'img4'];
                foreach ($imageFields as $imageField) {
                    if ($detail->$imageField) {
                        $imagePath = public_path($detail->$imageField);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
                // Delete the product detail
                $detail->delete();
            }
    
            // If the product has an image, delete it
            if ($product->image) {
                $imagePath = public_path($product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            // Finally, delete the product
            $product->delete();
    
            return ResponseHelper::Out('Product and its details deleted successfully.', null, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
        } catch (Exception $e) {
            return ResponseHelper::Out('Failed to delete product: ' . $e->getMessage(), null, 500);
        }
    }
    
    
}
