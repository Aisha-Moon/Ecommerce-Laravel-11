<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Product;
use App\Helper\ResponseHelper; 
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('brand','category')->get();
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
            $product = Product::with(['brand', 'category'])->findOrFail($id);
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
            $product->remark = $request->input('remark', 'regular'); 
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
            $product = Product::findOrFail($id);
            
            if ($product->image) {
                $imagePath = public_path($product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $product->delete();
            return ResponseHelper::Out('Product deleted successfully.', null, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
        } catch (Exception $e) {
            return ResponseHelper::Out('Failed to delete product: ' . $e->getMessage(), null, 500);
        }
    }
}
