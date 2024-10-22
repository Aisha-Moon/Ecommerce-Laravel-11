<?php

namespace App\Http\Controllers\Backend\Api;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use App\Http\Controllers\Controller;

class CustomerProfileController extends Controller
{
    

    // Display the profile page
  

    // Create or update a customer profile
    public function CreateProfile(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'cus_name' => 'required|string|max:255',
            'cus_add' => 'required|string|max:255',
            'cus_city' => 'required|string|max:255',
            'cus_state' => 'required|string|max:255',
            'cus_postcode' => 'required|string|max:20',
            'cus_country' => 'required|string|max:255',
            'cus_phone' => 'required|string|max:20',
            'cus_fax' => 'nullable|string|max:20',
            'ship_name' => 'required|string|max:255',
            'ship_add' => 'required|string|max:255',
            'ship_city' => 'required|string|max:255',
            'ship_state' => 'required|string|max:255',
            'ship_postcode' => 'required|string|max:20',
            'ship_country' => 'required|string|max:255',
            'ship_phone' => 'required|string|max:20',
        ]);

        // Get user ID from the request header
        $user_id = $request->header('id');
        $request->merge(['user_id' => $user_id]);

        // Create or update the profile
        $data = CustomerProfile::updateOrCreate(
            ['user_id' => $user_id],
            $request->only([
                'cus_name',
                'cus_add',
                'cus_city',
                'cus_state',
                'cus_postcode',
                'cus_country',
                'cus_phone',
                'cus_fax',
                'ship_name',
                'ship_add',
                'ship_city',
                'ship_state',
                'ship_postcode',
                'ship_country',
                'ship_phone',
                'user_id',
            ])
        );

        return ResponseHelper::Out('success', $data, 200);
    }

    // Read customer profile
    public function ReadProfile(Request $request)
    {
        $user_id = $request->header('id');
      
    
        try {
            $data = CustomerProfile::where('user_id', $user_id)->with('user')->firstOrFail();
            return ResponseHelper::Out('success', $data, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ResponseHelper::Out('fail', 'Profile not found.', 404);
        }
    }
    
    

    // Update customer profile
  

    // Delete customer profile
    public function DeleteProfile(Request $request)
    {
        // Get user ID from the request header
        $user_id = $request->header('id');

        // Find the profile
        $profile = CustomerProfile::where('user_id', $user_id)->first();

        if (!$profile) {
            return ResponseHelper::Out('fail', 'Profile not found.', 404);
        }

        // Delete the profile
        $profile->delete();

        return ResponseHelper::Out('success', 'Profile deleted successfully.', 200);
    }
}
