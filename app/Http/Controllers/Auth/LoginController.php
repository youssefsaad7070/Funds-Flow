<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Notifications\RegisterNotification;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Extract email and password from the request
        $inputs = $request->validated();

        $user = User::where('email', $inputs['email'])->first();

        if (!$user || !Auth::attempt($inputs) || !$user->email_verified_at) {
            // If user doesn't exist, authentication fails, or email is not verified, return a 401 response
            if ($user && !$user->email_verified_at) {
                $user->delete();
            }

            return response()->json([
                'status' => false,
                'message' => 'Email & Password do not match our records.',
            ], 401);
        }
        // Return a success response with user data and authentication token
        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'data' => $user
        ], 200);
    }
    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();

        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);

        // Revoke token
        $token->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }
    public function unauthorized()
    {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized!'
        ], 401);
    }
}
