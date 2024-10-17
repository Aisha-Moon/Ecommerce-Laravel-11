<?php
namespace App\Http\Middleware;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerifyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookies->get('token');

        
        // Check if the token is present
        if (!$token) {
            return ResponseHelper::Out('Token not found', null, 401);
            
        }
        $result = JWTToken::ReadToken($token);

        if ($result == "unauthorized") {
            return ResponseHelper::Out('unauthorized', null, 401);
        } else {
            // Set user details from token in the request headers
            $request->headers->set('email', $result->userEmail);
            $request->headers->set('id', $result->userID);
            $request->headers->set('role', $result->role); // Set the user's role in headers
            
            return $next($request);
        }
    }
 }
