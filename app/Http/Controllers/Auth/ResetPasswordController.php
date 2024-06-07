<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\OtpValidationRequest;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function otpValidation(OtpValidationRequest $request)
    {
        $otp2 = $this->otp->validate($request->email, $request->otp);

        if (!$otp2) {
            return response()->json([
                'error' => $otp2
            ], 401);
        }

        return response()->json(
            $otp2,
            200
        );
    }

    public function passwordReset(Request $request)
    {
        $input = $request->only(['email', 'password','password_confirmation']);
        
        $validator = Validator::make($input, [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'status' => true,
            'message' => 'Your password has been changed successfully'
        ], 200);
    }
}
