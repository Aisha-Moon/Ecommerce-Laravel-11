<?php


namespace App\Http\Controllers\Backend\Api;

use Exception;
use Throwable;
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

            // Prepare data to update
            $data = $request->except('img1', 'img2', 'img3', 'img4'); // Exclude image fields from the request

            // Handle image 1
            if ($request->hasFile('img1')) {
                $data['img1'] = $this->uploadImage($request, 'img1');
                $this->unlinkOldImg($productDetail, 'img1');
            } else {
                $data['img1'] = $productDetail->img1;
            }

            // Handle image 2
            if ($request->hasFile('img2')) {
                $data['img2'] = $this->uploadImage($request, 'img2');
                $this->unlinkOldImg($productDetail, 'img2');
            } else {
                $data['img2'] = $productDetail->img2;
            }

            // Handle image 3
            if ($request->hasFile('img3')) {
                $data['img3'] = $this->uploadImage($request, 'img3');
                $this->unlinkOldImg($productDetail, 'img3');
            } else {
                $data['img3'] = $productDetail->img3;
            }

            // Handle image 4
            if ($request->hasFile('img4')) {
                $data['img4'] = $this->uploadImage($request, 'img4');
                $this->unlinkOldImg($productDetail, 'img4');
            } else {
                $data['img4'] = $productDetail->img4;
            }



            $images = ['img1', 'img2', 'img3', 'img4'];
            foreach ($images as $img) {
                if ($request->input("remove$img") == 1) {
                    $this->unlinkOldImg($productDetail, $img);
                    $data[$img] = '';
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
