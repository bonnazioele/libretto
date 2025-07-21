<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);



        return response()->json([
            'message' => 'User registered successfully',

        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
    
        $user->tokens()
            ->where('expires_at', '<=', Carbon::now())
            ->delete();


        $validToken = $user->tokens()
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if ($validToken) {
    
            return response()->json([
                'message' => 'Token already exists (use your stored token)',
                'user' => $user,
                'has_valid_token' => true,
                'expires_at' => $validToken->expires_at
            ], 200);
        }


        $newToken = $user->createToken('auth_token', ['*'], now()->addDays(1));
        
        return response()->json([
            'message' => 'New token generated',
            'user' => $user,
            'token' => $newToken->plainTextToken,
            'expires_at' => $newToken->accessToken->expires_at
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        
        if ($token->expires_at < now()) {
            $token->delete();
            return response()->json([
                'message' => 'Token expired',
                'code' => 'token_expired'
            ], 401);
        }
        
        return response()->json($request->user());
    }

public function findReviews(Request $request)
{
    // Get the user_id from query parameters
    $userId = $request->query('user_id');

    // Check if user_id is provided
    if (!$userId) {
        return response()->json([
            'message' => 'The user_id field is required.',
            'errors' => [
                'user_id' => ['The user_id field is required.']
            ]
        ], 422);
    }

    // Find the reviews for the specified user
    $reviews = Review::where('user_id', $userId)->get();

    // Return the reviews in the response
    return response()->json([
        'message' => 'Reviews retrieved successfully',
        'reviews' => $reviews
    ]);
}
}