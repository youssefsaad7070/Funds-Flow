<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct(){
        $this->otp = new Otp;
    }

    public function sendEmailVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $user->notify(new EmailVerificationNotification());
        
        return response()->json([
            'status' => true ,
        ], 200);
    }
    public function email_verification(EmailVerificationRequest $request)
    {
        $inputs = $request->validated();

        $user = User::where('email', $request->email)->first();

        $otp2 = $this->otp->validate($inputs['email'], $request->verification_code);

        if (!$otp2->status){
            return response()->json([
                'error' => $otp2
            ], 401);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'status' => true ,
            'message' => 'Email Verified Successfully'
        ], 200);
    }
}
