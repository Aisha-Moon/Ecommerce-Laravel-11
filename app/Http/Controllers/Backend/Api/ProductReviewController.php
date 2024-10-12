<?php

namespace App\Http\Controllers\Backend\Api;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Helper\ResponseHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductReviewController extends Controller
{
    // Get all product reviews
    public function index()
    {
        $productReviews = ProductReview::with(['product:id,title', 'customerProfile:id,cus_name'])->get();
        return ResponseHelper::Out('Product reviews retrieved successfully.', $productReviews, 200);
    }
    

    // Get a single product review by ID
    public function show($id)
    {
        try {
            $productReview = ProductReview::with(['product:id,title', 'customerProfile:id,cus_name'])->findOrFail($id);
            return ResponseHelper::Out('Product review retrieved successfully.', $productReview, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product review not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error retrieving product review: ' . $e->getMessage());
            return ResponseHelper::Out('Error retrieving product review.', null, 500);
        }
    }

    // Store a new product review
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'description' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5',
                'product_id' => 'required|exists:products,id',
                'customer_id' => 'required|exists:customer_profiles,id',
            ]);

            // Create the product review
            $productReview = ProductReview::create($request->all());
            return ResponseHelper::Out('Product review created successfully.', $productReview, 201);
        } catch (Exception $e) {
            Log::error('Error creating product review: ' . $e->getMessage());
            return ResponseHelper::Out('Error creating product review.', null, 500);
        }
    }

    // Update a product review
    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'description' => 'nullable|string|max:1000',
                'rating' => 'nullable|integer|min:1|max:5',
                'product_id' => 'nullable|exists:products,id',
                'customer_id' => 'nullable|exists:customer_profiles,id',
            ]);

            // Find the product review
            $productReview = ProductReview::findOrFail($id);

            // Update the product review
            $productReview->update($request->all());
            return ResponseHelper::Out('Product review updated successfully.', $productReview, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product review not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error updating product review: ' . $e->getMessage());
            return ResponseHelper::Out('Error updating product review.', null, 500);
        }
    }

    // Delete a product review
    public function destroy($id)
    {
        try {
            // Find and delete the product review
            $productReview = ProductReview::findOrFail($id);
            $productReview->delete();
            return ResponseHelper::Out('Product review deleted successfully.', null, 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Product review not found.', null, 404);
        } catch (Exception $e) {
            Log::error('Error deleting product review: ' . $e->getMessage());
            return ResponseHelper::Out('Error deleting product review.', null, 500);
        }
    }
}
