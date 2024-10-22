<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
     public function ProfilePage()
     {
         return view('frontend.pages.profile-page');
     }
}