<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('email', $request->only('email'))->first();
        if(!$user || !$user->email){
            return response()->json([
                'status' => false,
                'error' => 'No Record Found!, Incorrect email-address provided'
            ], 404);
        }
        $user->notify(new ResetPasswordVerificationNotification());

        return response()->json([
            'status' => true,
            'message' => 'Verification code has been sent to your email :)'
        ],200);
    }
}
