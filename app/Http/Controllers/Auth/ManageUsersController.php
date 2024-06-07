<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GetPhotoUrlController;

class ManageUsersController extends Controller
{
    public function indexInvestors()
    {
        $users = User::where('role', 'investor')
            ->whereNotNull('email_verified_at')
            ->with('investor:national_id,user_id')
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No investor found !'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    public function indexBusinesses()
    {
        $users = User::where('role', 'business')
            ->whereNotNull('email_verified_at')
            ->with('business:tax_card_number,user_id')
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No business found !'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => true,
                'message' => 'No user found !'
            ], 404);
        }

        if ($user->hasRole('investor')) {
            $user->load('investor');
        } else if ($user->hasRole('business')) {
            $user->load('business');
        } else if ($user->hasRole('admin')) {
            $user->load('admin');
        }

        $user->makeHidden(['roles', 'name', 'email']);

        GetPhotoUrlController::transformOne($user);
        GetPhotoUrlController::transformCardPhoto($user);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function approve($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Update the is_approved attribute
        $user->is_approved = true;
        $user->save();

        // Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'User approved successfully!!',
        ]);
    }

    public function disApprove($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Update the is_approved attribute
        $user->is_approved = false;
        $user->save();

        // Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'User disapproved successfully!!',
        ]);
    }
}
