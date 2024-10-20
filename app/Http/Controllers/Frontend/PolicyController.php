<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Policy;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{

public function PolicyPage()
    {
        return view('frontend.pages.policy_page');
    }


    function PolicyByType(Request $request){
      return Policy::where('type','=',$request->type)->first();
    }
}