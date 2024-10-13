<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
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

    public function store(StoreProductSliderRequest $request)
    {
        try {
            // Store uploaded image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/product_sliders'), $filename);
            }

            $productSlider = ProductSlider::create(array_merge($request->validated(), ['image' => $filename]));

            return ResponseHelper::Out('Product slider created successfully.', $productSlider, 201);
        } catch (\Exception $e) {
            Log::error('Error creating product slider: ' . $e->getMessage());
            return ResponseHelper::Out('Error creating product slider.', null, 500);
        }
    }

    public function show($id)
    {
        try {
            $productSlider = ProductSlider::with('product:id,title')->findOrFail($id);
            return ResponseHelper::Out('Product slider retrieved successfully.', $productSlider, 200);
        } catch (\ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (\Exception $e) {
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
        } catch (\ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (\Exception $e) {
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
        } catch (\ModelNotFoundException $e) {
            return ResponseHelper::Out('Product slider not found.', null, 404);
        } catch (\Exception $e) {
            Log::error('Error deleting product slider: ' . $e->getMessage());
            return ResponseHelper::Out('Error deleting product slider.', null, 500);
        }
    }
}
