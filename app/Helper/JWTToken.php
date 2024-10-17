<?php

namespace App\Helper;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JWTToken
{
    public static function CreateToken($userEmail, $userID, $role)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 24 * 60 * 60, 
            'userEmail' => $userEmail,
            'userID' => $userID,
            'role' => $role  // Add role to the token payload
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    

    public static function ReadToken($token)
    {
        try {
            if($token == null){
                return 'unauthorized';
            } else {
                $key = env('JWT_KEY');
                return JWT::decode($token, new Key($key, 'HS256'));
            }
        } catch (Exception $e) {
            // Log or print the exception message for debugging
            Log::error('JWT Decode Error: ' . $e->getMessage());
            return 'unauthorized';
        }
    }
    
}
