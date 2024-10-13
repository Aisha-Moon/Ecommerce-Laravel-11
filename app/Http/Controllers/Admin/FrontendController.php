<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;


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
}