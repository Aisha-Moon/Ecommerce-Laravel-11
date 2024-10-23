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
    
        // Decode the token
        $result = JWTToken::ReadToken($token);
    
        if ($result === 'unauthorized') {
            return response()->json(['message' => 'unauthorized'], 401);
        }
    
        // Set user details in the request headers
        $request->headers->set('email', $result->userEmail);
        $request->headers->set('id', $result->userID);
        $request->headers->set('role', $result->role);
    
        // Store role in session
        session(['role' => $result->role]);
    
        // Check if the route requires admin access and user is not an admin
        if ($request->is('admin/*') && $result->role !== 'admin') {
            return response()->json(['message' => 'Access denied: Admins only'], 403);
        }
    
        return $next($request);
    }
    
}
