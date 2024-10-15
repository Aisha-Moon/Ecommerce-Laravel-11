<?php


namespace App\Http\Controllers\Backend\Api;

use Exception;
use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\ProductDetails;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductDetailsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductDetailsController extends Controller
{
    public function index()
    {
        $productDetails = ProductDetails::with(['product.brand', 'product.category'])->get();

        return ResponseHelper::Out('Product details retrieved successfully.', $productDetails, 200);
    }


    // Retrieve a specific product detail
    public function show($id)
    {
        $productDetail = ProductDetails::with('product', 'product.brand', 'product.category')->find($id);

        if (!$productDetail) {
            return ResponseHelper::Out('Product detail not found.', null, 404);
        }

        return ResponseHelper::Out('Product detail retrieved successfully.', $productDetail, 200);
    }

    public function store(StoreProductDetailsRequest $request)
    {
        try {
            // Validate that the product ID exists
            $product = Product::findOrFail($request->input('product_id'));
    
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
    
            // Create product details with the associated product ID
            $productDetail = ProductDetails::create(array_merge($request->only(['des', 'color', 'size', 'product_id']), $imagePaths));
    
            return ResponseHelper::Out('Product detail created successfully.', $productDetail, 201);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product not found.', null, 404);
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

        // Prepare data to update
        $data = $request->only('des', 'color', 'size'); 

        // Handle images
        foreach (['img1', 'img2', 'img3', 'img4'] as $imgField) {
            if ($request->hasFile($imgField)) {
                $data[$imgField] = $this->uploadImage($request, $imgField);
                $this->unlinkOldImg($productDetail, $imgField);
            } else {
                $data[$imgField] = $productDetail->$imgField;
            }
        }

        // Update the product detail with new data
        $productDetail->update($data);

        return ResponseHelper::Out('Product detail updated successfully.', $productDetail, 200);
    } catch (Throwable $th) {
        // Log and return the error
        Log::error('Error updating product detail: ' . $th->getMessage());
        return ResponseHelper::Out('An error occurred while updating product detail.', null, 500);
    }
}

protected function uploadImage($request, $fieldName)
{
    $file = $request->file($fieldName);
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = "uploads/product_details/$filename";

    // Move the image to the destination
    $file->move(public_path('uploads/product_details'), $filename);

    return $path;
}

    protected function unlinkOldImg($productDetail, $fieldName)
    {
        // Check if the image exists and delete it
        $oldImagePath = $productDetail->$fieldName;
        if ($oldImagePath && file_exists(public_path($oldImagePath))) {
            unlink(public_path($oldImagePath));
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
