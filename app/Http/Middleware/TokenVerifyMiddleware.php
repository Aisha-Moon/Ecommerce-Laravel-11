<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TokenVerifyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the token from the cookie
        $token = $request->cookie('token');
    
        // Log the token before decoding
        Log::info('Token to decode: ' . $token);
    
        // Decode the token
        $result = JWTToken::ReadToken($token);
    
        Log::info('JWT Decode Result:', (array) $result);
    
        if ($result === 'unauthorized') {
            return response()->json(['message' => 'unauthorized'], 401);
        }
    
        // Set user details in the request headers
        $request->headers->set('email', $result->userEmail);
        $request->headers->set('id', $result->userID);
        $request->headers->set('role', $result->role);
    
        return $next($request);
    }
    
}