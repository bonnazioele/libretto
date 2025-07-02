<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        
        // Delete all existing tokens for this user
        $user->tokens()->delete();
        
        // Create new token that expires in 1 day
        $token = $user->createToken('auth_token', ['*'], now()->addDay());
        
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        // Check if token is expired
        $token = $request->user()->currentAccessToken();
        
        if ($token->expires_at < now()) {
            return response()->json([
                'message' => 'Token expired',
                'code' => 'token_expired'
            ], 401);
        }
        
        return response()->json($request->user());
    }
}