<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

     public function LoginPage()
     {
         return view('frontend.pages.login-page');
     }
 
     public function VerifyPage()
     {
         return view('frontend.pages.verify-page');
     }


}