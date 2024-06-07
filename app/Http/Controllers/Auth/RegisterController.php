<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Business;
use App\Models\Investor;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\RegisterNotification;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Notifications\EmailVerificationNotification;

class RegisterController extends Controller
{
    public function register(RegisterationRequest $request)
    {
        try {
            // Extract only the relevant input fields from the request
            $input = $request->validated();

            // Determine the default photo path based on the user's role and gender
            switch ($input['role']) {
                case 'investor':
                    // Assign default photo based on gender
                    $photoPath = isset($input['gender']) && $input['gender'] === 'Female' ? 'DefaultUserImages/Female.jpg' : 'DefaultUserImages/Male.jpg';
                    break;

                case 'business':
                    // Assign default business photo
                    $photoPath = 'DefaultUserImages/Business.jpg';
                    break;
            }

            if ($request->hasFile('id_card_photo')) {
                $idCardPhotoPathFile = $request->file('id_card_photo');
                $idCardPhotoPath = $idCardPhotoPathFile->store('IdCardPhotos', 'custom'); // 'public' disk, in 'photos' directory
            }
            
            // Create the user with the validated data
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'role' => $input['role'],
                'photo' => $photoPath,
                'id_card_photo' => $idCardPhotoPath ,
                'password' => Hash::make($input['password']),
            ]);

            // Assign the role to the user
            $role = Role::findByName($input['role'], 'web');
            $user->assignRole($role);

            // Notify the user of successful registration
            $user->notify(new RegisterNotification());

           
            // Optionally, notify the user to verify their email
            $user->notify(new EmailVerificationNotification());

            // If the user is a business, create a related business record
            if ($user->hasRole('business')) {
                Business::create([
                    'user_id' => $user->id,
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'tax_card_number' => $input['tax_card_number'],
                    'description' => $input['description'],
                ]);
            }
            // If the user is an investor, create a related investor record
            else if ($user->hasRole('investor')) {
                Investor::create([
                    'user_id' => $user->id,
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'gender' => $input['gender'],
                    'national_id' => $input['national_id'],
                ]);
            }

            // Return a success response with a token
            return response()->json([
                'status' => true,
                'message' => 'User ' . $user->name . ' Created Successfully',
                'token' => $user->createToken('user', ['app:all'])->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            // Catch any exceptions and return a 500 response with the error message
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
