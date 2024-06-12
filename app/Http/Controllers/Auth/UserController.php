<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetPhotoUrlController;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        
        GetPhotoUrlController::transformOne($user);

        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }
}
