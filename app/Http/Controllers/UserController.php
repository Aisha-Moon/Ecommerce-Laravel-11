<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserLogin(Request $request)
    {
        try {
            $UserEmail = $request->UserEmail;
            $OTP = rand(100000, 999999);
            $details = ['otp' => $OTP];
            Mail::to($UserEmail)->send(new OTPMail($details));
            User::updateOrCreate(['email' => $UserEmail], ['email' => $UserEmail, 'otp' => $OTP]);
            return ResponseHelper::Out('success', "A 6 Digit OTP has been send to your email address", 200);
        } catch (Exception $e) {
            return ResponseHelper::Out('fail', $e->getMessage(), 200);
        }
    }
  
    // VerifyLogin method in UserController
    public function VerifyLogin($UserEmail, $OTP)
    {
        $verification = User::where('email', $UserEmail)->where('otp', $OTP)->first();
    
        if ($verification) {
            User::where('email', $UserEmail)->where('otp', $OTP)->update(['otp' => '0']);
    
            // Determine the role based on 'is_admin' field
            $role = $verification->is_admin == 1 ? 'admin' : 'user';
    
            // Set role in session
            session(['role' => $role]);
    
            // Create JWT token with user role
            $token = JWTToken::CreateToken($verification->email, $verification->id, $role);
    
            // Prepare the response with the token and redirect URL
            $redirectUrl = $verification->is_admin == 1 ? '/admin/dashboard' : '/';
    
            // Return the token in a cookie with redirect information
            return response()->json(['token' => $token, 'redirect' => $redirectUrl])
                             ->cookie('token', $token, 60, null, null, false, false);
        } else {
            return ResponseHelper::Out('fail', null, 401);
        }
    }
    
    



    function UserLogout()
    {
        return redirect('/')->cookie('token', '', -1);
    }


}
