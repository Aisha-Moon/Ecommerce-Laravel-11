<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\ProductSlider;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductSliderRequest; 
use App\Http\Requests\UpdateProductSliderRequest; 

class ProductSliderController extends Controller
{
    public function index()
    {
        try {
            $productSliders = ProductSlider::with('product:id,title')->get();
            return ResponseHelper::Out('Product sliders retrieved successfully.', $productSliders, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving product sliders: ' . $e->getMessage());
            return ResponseHelper::Out('Error retrieving product sliders.', null, 500);
        }
    }

    public function store(StoreProductSliderRequest $request, $id = null)
    {
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/product_sliders'), $filename);
                $imagePath = $filename;
            }
    
            // If an ID is provided, update the existing record; otherwise, create a new one
            if ($id) {
                $productSlider = ProductSlider::findOrFail($id);
                $productSlider->update(array_merge($request->validated(), ['image' => $imagePath ?? $productSlider->image]));
                $message = 'Product slider updated successfully.';
            } else {
                $productSlider = ProductSlider::create(array_merge($request->validated(), ['image' => $imagePath]));
                $message = 'Product slider created successfully.';
            }
    
            return ResponseHelper::Out($message, $productSlider, $id ? 200 : 201);
        } catch (Exception $e) {
            Log::error('Error in product slider operation: ' . $e->getMessage());
            return ResponseHelper::Out('Error processing product slider.', null, 500);
        }
    }
    

    public function show($id)
    {
        try {
            $productSlider = ProductSlider::with('product:id,title')->findOrFail($id);
            return ResponseHelper::Out('Product slider retrieved successfully.', $productSlider, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error retrieving product slider: ' . $e->getMessage());
            return ResponseHelper::Out('Error retrieving product slider.', null, 500);
        }
    }

    public function update(UpdateProductSliderRequest $request, $id)
    {
        try {
            $productSlider = ProductSlider::findOrFail($id);

            // Store uploaded image if it exists
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/product_sliders'), $filename);
                $request->merge(['image' => $filename]); // update image in request data
            }

            $productSlider->update($request->validated());

            return ResponseHelper::Out('Product slider updated successfully.', $productSlider, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error updating product slider: ' . $e->getMessage());
            return ResponseHelper::Out('Error updating product slider.', null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $productSlider = ProductSlider::findOrFail($id);
            $productSlider->delete();
            return ResponseHelper::Out('Product slider deleted successfully.', null, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error deleting product slider: ' . $e->getMessage());
            return ResponseHelper::Out('Error deleting product slider.', null, 500);
        }
    }
}
