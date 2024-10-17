<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
            // $request->validate([
            //     'description' => 'required|string|max:1000',
            //     'rating' => 'required|integer|min:1|max:5',
            //     'product_id' => 'required|exists:products,id',
            // ]);
    
            // Get customer_id from the request header
            $customer_id = $request->header('id');
            $profile = CustomerProfile::where('user_id', $customer_id)->first();
    
            // Check if customer_id is valid
            if (!$customer_id || !$profile) {
                return ResponseHelper::Out('Invalid customer ID.', null, 400);
            }

            // Update or create the product review
            $productReview = ProductReview::updateOrCreate(
                [
                    'product_id' => $request->product_id, // Condition to check existing review
                    'customer_id' => $profile->id, // Condition to check existing review
                ],
                [
                    'description' => $request->description,
                    'rating' => $request->rating,
                ]
            );
    
            return ResponseHelper::Out('Product review saved successfully.', $productReview->load('customerProfile'), 200);
        } catch (Exception $e) {
            Log::error('Error saving product review: ' . $e->getMessage());
            return ResponseHelper::Out('Error saving product review.', null, 500);
        }
    }
    
    
    
    
    


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
