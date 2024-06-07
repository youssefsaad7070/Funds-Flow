<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Models\Business;
use App\Models\Investor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\InvestmentOpportunity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GetPhotoUrlController;
use Illuminate\Contracts\Support\ValidatedData;
use App\Http\Requests\Auth\ProfileUpdateRequest;


class ProfileController extends Controller
{
    // Method to get the profile of the authenticated user
    public function getProfile()
    {
        // Retrieve the authenticated user
        $user = User::where('id', auth()->id())->first();

        // Getting the user's photo URL before sending it to the front-end
        GetPhotoUrlController::transformOne($user);

        // Check if the user has the 'investor' role
        if ($user->hasRole('investor')) {

            // Retrieve investor-specific data
            $investorData = $user->investors()->first();

            // Retrieve invested opportunities with pivot fields
            $investedOpportunities = $user->investedOpportunities()
                ->withPivot('amount', 'created_at', 'updated_at')
                ->get();

            // Transform the photo URLs of the invested opportunities
            GetPhotoUrlController::transform($investedOpportunities);

            // Return a JSON response with the investor's profile data
            return response()->json([
                'status' => true,
                'role' => $user->role,
                'photo' => $user->photo,
                'data' => $investorData,
                'invested_opportunities' => $investedOpportunities->isEmpty() ? 'The User did not invest in any investment opportunity' : $investedOpportunities,
            ], 200);

        // Check if the user has the 'business' role
        } else if ($user->hasRole('business')) {

            // Retrieve business-specific data
            $businessData = $user->businesses()->first();

            // Retrieve approved investment opportunities
            $Opportunities = $user->investmentOpportunities()->where('approved', true)->get();

            // Retrieve past investment opportunities
            $investmentHistory = $user->investmentOpportunities()
                ->where('end_date', '<', now())
                ->get();

            // Transform the photo URLs of the opportunities and investment history
            GetPhotoUrlController::transform($Opportunities);
            GetPhotoUrlController::transform($investmentHistory);

            // Return a JSON response with the business's profile data
            return response()->json([
                'status' => true,
                'role' => $user->role,
                'photo' => $user->photo,
                'data' => $businessData,
                'business_opportunities' => $Opportunities->isEmpty() ? 'No Investment Opportunity found !' : $Opportunities,
                'investment_history' => $investmentHistory->isEmpty() ? 'No History found !' : $investmentHistory
            ], 200);

        // Check if the user has the 'admin' role
        } else if ($user->hasRole('admin')) {

            // Retrieve admin-specific data
            $admin = $user->admins()->first();

            // Retrieve inactive investment opportunities managed by the admin
            $opportunity = $user->investmentOpportunitiesAdmin()
                ->with('category')
                ->where('active', false)
                ->get();

            // Transform the photo URLs of the opportunities
            GetPhotoUrlController::transform($opportunity);

            // Return a JSON response with the admin's profile data
            return response()->json([
                'status' => true,
                'role' => $user->role,
                'photo' => $user->photo,
                'data' => $admin,
                'opportunities' => $opportunity
            ], 200);
        }
    }

    // Method to update the profile of the authenticated user
    public function updateProfile(Request $request)
    {
        // Retrieve the authenticated user
        $user = User::where('id', auth()->id())->first();
        
        // Check if the user has the 'investor' role
        if ($user->hasRole('investor')) {
            $input = $request->only([
                'name', 'email', 'national_id', 'about',
                'phone', 'age', 'nationality', 'photo'
            ]);

            // Retrieve the investor record associated with the user
            $investor = Investor::where('user_id', auth()->id())->first();

            // Validate the input data
            $validateData = Validator::make(
                $input,
                [
                    'name' => 'sometimes|max:100',
                    'email' => [
                        'sometimes',
                        'email',
                        'unique:users,email,' . $user->id,
                        'unique:investors,email, ' . $investor->id,
                        'gmail_domain'
                    ],
                    'national_id' => 'sometimes|min:14|max:14|unique:investors,national_id, ' . $investor->id,
                    'phone' => 'sometimes',
                    'age' => 'sometimes',
                    'about' => 'sometimes',
                    'nationality' => 'sometimes',
                    'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]
            );

            // Handle validation failure
            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            // Get the validated data
            $validatedData = $validateData->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {

                // Delete the existing photo if it exists
                $existingPhotoPath = $user->photo;
                if ($existingPhotoPath && str_starts_with($existingPhotoPath, 'UserImages/')) {
                    Storage::disk('custom')->delete($existingPhotoPath);
                }

                // Store the new photo
                $photoFile = $request->file('photo');
                $photoPath = $photoFile->store('UserImages', 'custom');
                $validatedData['photo'] = $photoPath;
            }

            // Separate user and investor data
            $userData = array_intersect_key($validatedData, array_flip(['name', 'email', 'photo']));
            $user->update($userData);

            $investorData = array_intersect_key($validatedData, array_flip(['name', 'email', 'national_id', 'phone', 'about', 'age', 'nationality']));
            $investor->update($investorData);

            // Return a JSON response with the updated data
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'data' => $validatedData
            ]);

        // Check if the user has the 'business' role
        } else if ($user->hasRole('business')) {
            $input = $request->only([
                'name', 'email', 'tax_card_number', 'phone',
                'current_address', 'description', 'nationality', 'photo'
            ]);

            // Retrieve the business record associated with the user
            $business = Business::where('user_id', auth()->id())->first();

            // Validate the input data
            $validateData = Validator::make(
                $input,
                [
                    'name' => 'sometimes|max:100',
                    'email' => [
                        'sometimes',
                        'email',
                        'unique:users,email,' . $user->id,
                        'unique:businesses,email, ' . $business->id,
                        'gmail_domain'
                    ],
                    'tax_card_number' => 'sometimes|min:12|max:12|unique:businesses,tax_card_number,' . $business->id,
                    'phone' => 'sometimes',
                    'current_address' => 'sometimes',
                    'nationality' => 'sometimes',
                    'description' => 'sometimes',
                    'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]
            );

            // Handle validation failure
            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            // Get the validated data
            $validatedData = $validateData->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {

                // Delete the existing photo if it exists
                $existingPhotoPath = $user->photo;
                if ($existingPhotoPath && str_starts_with($existingPhotoPath, 'UserImages/')) {
                    Storage::disk('custom')->delete($existingPhotoPath);
                }

                // Store the new photo
                $photoFile = $request->file('photo');
                $photoPath = $photoFile->store('UserImages', 'custom');
                $validatedData['photo'] = $photoPath;
            }

            // Separate user and business data
            $userData = array_intersect_key($validatedData, array_flip(['name', 'email', 'photo']));
            $user->update($userData);

            $businessData = array_intersect_key($validatedData, array_flip(['name', 'email', 'tax_card_number', 'phone', 'description', 'current_address', 'nationality']));
            $business->update($businessData);

            // Return a JSON response with the updated data
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'data' => $validatedData
            ]);

        // Check if the user has the 'admin' role
        } else if ($user->hasRole('admin')) {

            $input = $request->only([
                'name', 'email', 'password', 'photo'
            ]);

            // Retrieve the admin record associated with the user
            $admin = Admin::where('user_id', auth()->id())->first();
        
            // Validate the input data
            $validateData = Validator::make(
                $input,
                [
                    'name' => 'sometimes|max:100',
                    'email' => [
                        'sometimes',
                        'email',
                        'unique:users,email, ' . $user->id,
                        'unique:admins,email, ' . $admin->id,
                        'gmail_domain'
                    ],
                    'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]
            );

            // Handle validation failure
            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            // Get the validated data
            $validatedData = $validateData->validated();
        
            // Handle photo upload
            if ($request->hasFile('photo')) {

                // Delete the existing photo if it exists
                $existingPhotoPath = $user->photo;
                if ($existingPhotoPath && str_starts_with($existingPhotoPath, 'UserImages/')) {
                    Storage::disk('custom')->delete($existingPhotoPath);
                }

                // Store the new photo
                $photoFile = $request->file('photo');
                $photoPath = $photoFile->store('UserImages', 'custom');
                $validatedData['photo'] = $photoPath;
            }

            // Separate user and admin data
            $userData = array_intersect_key(
                $validatedData,
                array_flip([
                    'name', 'email', 'photo'
                ])
            );
            $user->update($userData);

            $adminData = array_intersect_key(
                $validatedData,
                array_flip([
                    'name', 'email'
                ])
            );
            $admin->update($adminData);
            
            // Return a JSON response with the updated data
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'data' => $validatedData,
            ]);
        }
    }

    // Method to update admin-specific profile data
    public function updateAdminProfile(Request $request, $uuid)
    {
        // Retrieve the authenticated user
        $user = User::where('id', auth()->id())->first();

        // Check if the user has the 'admin' role
        if ($user->hasRole('admin')) {
            
            $input = $request->only([
                'commission_percentage', 'commission_amount'
            ]);
            
            // Retrieve the specific investment opportunity managed by the admin
            $opportunity = InvestmentOpportunity::where('uuid', $uuid)->where('admin_id', auth()->id())->first();

            // If the opportunity is not found, return an error response
            if (!$opportunity){
                return response()->json([
                    'status' => false,
                    'message' => 'No Investment Opportunities found!'
                ]);
            }

            // Validate the input data
            $validateData = Validator::make(
                $input,
                [    
                    'commission_percentage' => 'sometimes',
                    'commission_amount' => 'sometimes',
                ]
            );

            // Handle validation failure
            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            // Get the validated data
            $validatedData = $validateData->validated();

            // Update the investment opportunity with the validated data
            $opportunityData = array_intersect_key(
                $validatedData,
                array_flip([
                    'commission_percentage', 'commission_amount'
                ])
            );
            $opportunity->update($opportunityData);
            
            // Return a JSON response with the updated data
            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'data' => $validatedData,
            ]);
        }
        // Return an error response if the user is not an admin
        return response()->json([
            'status' => false,
            'message' => 'No Admin found!',
        ], 404);
    }
}