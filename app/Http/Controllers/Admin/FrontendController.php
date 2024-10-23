<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FrontendController extends Controller
{
    function dashboardPage()
    {
        return view('admin.dashboard.dashboard-page');

    }

    function productPage()
    {
        return view('admin.dashboard.product-page');
    }

    function categoryPage()
    {
        return view('admin.dashboard.category-page');
    }

    function brandPage()
    {
        return view('admin.dashboard.brand-page');
    }
    public function profilePage()
    {
        return view('admin.dashboard.profile-page'); // Ensure you have this view created
    }

    public function getProfileDetails(Request $request)
    {
        // Get the admin's ID from the request header
        $adminId = $request->header('id'); // Adjust if your header has a different name
 
        // Retrieve the admin user from the database
        $admin = User::where('id', $adminId)->first();
    
        // Check if the admin user exists
        if ($admin) {
            return response()->json(['data' => ['admin_email' => $admin->email]]);
        } else {
            return response()->json(['error' => 'Admin not found'], 404);
        }
    }

}