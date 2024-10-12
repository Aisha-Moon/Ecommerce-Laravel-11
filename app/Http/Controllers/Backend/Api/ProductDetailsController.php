<?php


namespace App\Http\Controllers\Backend\Api;

use Exception;
use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Helper\ResponseHelper; 
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductDetailsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductDetailsController extends Controller
{
    public function index()
    {
        $productDetails = ProductDetails::all();
        return ResponseHelper::Out('Product details retrieved successfully.', $productDetails, 200);
    }

    // Retrieve a specific product detail
    public function show($id)
    {
        $productDetail = ProductDetails::find($id);
        
        if (!$productDetail) {
            return ResponseHelper::Out('Product detail not found.', null, 404);
        }

        return ResponseHelper::Out('Product detail retrieved successfully.', $productDetail, 200);
    }   
    public function store(StoreProductDetailsRequest $request)
    {
        try {
            // Store uploaded images
            $imagePaths = [];
            foreach (['img1', 'img2', 'img3', 'img4'] as $imgField) {
                if ($request->hasFile($imgField)) {
                    $img = $request->file($imgField);
                    $filename = time() . '_' . $img->getClientOriginalName();
                    $imagePaths[$imgField] = "uploads/product_details/$filename";
                    $img->move(public_path('uploads/product_details'), $filename);
                }
            }
    
            $productDetail = ProductDetails::create(array_merge($request->all(), $imagePaths));
    
            return ResponseHelper::Out('Product detail created successfully.', $productDetail, 201);
        } catch (Exception $e) {
            Log::error('Error creating product detail: ' . $e->getMessage());
    
            return ResponseHelper::Out('An error occurred while creating product detail.', null, 500);
        }
    }
    
    public function update(StoreProductDetailsRequest $request, $id)
    {
        try {
            $productDetail = ProductDetails::find($id);
            if (!$productDetail) {
                return ResponseHelper::Out('Product detail not found.', null, 404);
            }
    
            // Store uploaded images and handle existing images
            $imagePaths = [];
            $imageArray = ['img1', 'img2', 'img3', 'img4'];
            foreach ($imageArray as $imgField) {
                if ($request->hasFile($imgField)) {
                    $img = $request->file($imgField);
                    $filename = time() . '_' . $img->getClientOriginalName();
                    $imagePaths[$imgField] = "uploads/product_details/$filename";
                    $img->move(public_path('uploads/product_details'), $filename);
    
                    $this->deleteExistingImage($productDetail->$imgField);
                }
            }
    
         
            $productDetail->update(array_merge($request->all(), $imagePaths));
    
            return ResponseHelper::Out('Product detail updated successfully.', $productDetail, 200);
        } catch (Exception $e) {
            // Log the exception message for debugging
            Log::error('Error updating product detail: ' . $e->getMessage());
    
            // Return a generic error response
            return ResponseHelper::Out('An error occurred while updating product detail.', null, 500);
        }
    }
    
  


    protected function deleteExistingImage($imagePath)
    {
        if ($imagePath) {
            $existingImagePath = public_path($imagePath);
            if (file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }
        }
    }
    
    public function destroy($id)
    {
        $productDetail = ProductDetails::find($id);
    
        if (!$productDetail) {
            return ResponseHelper::Out('Product detail not found.', null, 404);
        }
    
      
        $imageFields = ['img1', 'img2', 'img3', 'img4'];
    
        foreach ($imageFields as $imgField) {
            $this->deleteExistingImage($productDetail->$imgField);
        }
    
        $productDetail->delete();
    
        return ResponseHelper::Out('Product detail deleted successfully.', null, 200);
    }
    
    

}