<?php

namespace App\Http\Controllers\Opportunities;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\InvestmentOpportunity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GetPhotoUrlController;
use App\Http\Requests\Opportunities\StoreOpportunityRequest;
use App\Models\Business;

class InvestmentOpportunityController extends Controller
{
    // Getting all the investment opportunities associated to a specific category
    public function index($uuid)
    {
        // Are getting the category by wildcard {uuid}
        $categoryUUID = $uuid;
        $category = Category::where('uuid', $categoryUUID)->first();

        // If category not found so we will send 404 not found response
        if (!$category) {
            return response()->json([
                'status' => false,
                'data' => 'Category not found'
            ], 404);
        }

        // Checking the end date to send the available opportunities at the next step (by updating 'active' attribute)
        $investmentOpportunities = InvestmentOpportunity::all();
        if ($investmentOpportunities) {
            foreach ($investmentOpportunities as $opportunity) {
                if ($opportunity->end_date < now()) {
                    $opportunity->update([
                        'active' => false,
                    ]);
                }
            }
        }

        // Getting the approved and active investment opportunities
        $investmentOpportunities = $category->investmentOpportunities()->where('approved', true)->where('active', true)->get();

        // Checking if the $investmentOpportunities is empty or not 
        if ($investmentOpportunities->isEmpty()) {
            return response()->json([
                'status' => false,
                'data' => 'There is no Advertisment at this category'
            ], 200);
        }

        // Getting the url for the photo to send it 
        GetPhotoUrlController::transform($investmentOpportunities);

        // Sending the investment Opportunities attached to specific category
        return response()->json([
            'status' => true,
            'data' => $investmentOpportunities
        ], 200);
    }

    // Showing a specific investment opportunity by the {uuid}
    public function show($uuid)
    {
        // Getting the investment opportunity details by the given id 
        $opportunityUUID = $uuid;
        $opportunity = InvestmentOpportunity::where('uuid', $opportunityUUID)
            ->with('category')
            ->get();

        // Checking if the $opportunity is empty or not 
        if ($opportunity->isEmpty()) {
            return response()->json([
                'status' => false,
                'data' => 'There is no Advertisment with this id'
            ], 404);
        }

        // Getting the url for the photo to send it 
        GetPhotoUrlController::transform($opportunity);

        // Returining the successfull response to the front end (the required investment opportunity)
        return response()->json([
            'status' => true,
            'data' => $opportunity,
        ], 200);
    }

    // Creating a new investment opportunity 
    public function store(StoreOpportunityRequest $request)
    {
        // Validating the inputs from the investment opportunity form 
        $validatedData = $request->validated();

        // Storing the photo in /OpportunityImages at public folder and getting the path and storing it into $validatedData
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');

            // Store the file and get the storage path
            $photoPath = $photoFile->store('OpportunityImages', 'custom'); // 'public' disk, in 'photos' directory

            // Add the photo path to the validated data
            $validatedData['photo'] = $photoPath;
        } else {
            $photoPath = 'DefaultOpportunityImages/DefultOpportunity.jpg';
            $validatedData['photo'] = $photoPath;
        }

        // Getting the category ID to store it as a foreign key at investment opportunity table 
        $category = Category::where('name', $request->category_name)->first();
        $validatedData['category_id'] = $category->id;

        $validatedData['business_id'] = auth()->id();
        $validatedData['remaining_amount'] = $request->amount_needed;

        $business = Business::where('user_id', auth()->id())->first();

        $business->update([
            'total_funds_raised' => ($business->total_funds_raised ?? 0) + $validatedData['amount_needed']
        ]);

        // Storing inputs into investmentOpportunity table
        $investmentOpportunity = InvestmentOpportunity::create($validatedData);

        // Returining the successfull response to the front end (Investment opportunity created successfully!)
        return response()->json([
            'status' => true,
            'message' => 'Investment opportunity created successfully!',
            'data' => $investmentOpportunity
        ], 201);
    }

    // Getting the investment opportunity data and id to update it 
    public function update(Request $request, $uuid)
    {
        // Validate the data sent to be updated at the database
        $validateData = Validator::make(
            $request->all(),
            [
                'business_name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'amount_needed' => 'sometimes|numeric|min:0',
                'potential_risks' => 'sometimes|string',
                'future_growth' => 'sometimes|string',
                'products_or_services' => 'sometimes|string',
                'returns_percentage' => 'sometimes|string|min:0|max:100',
                'company_valuation' => 'sometimes|string|min:0',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'revenues' => 'sometimes|string|min:0',
                'net_profit' => 'sometimes|string|min:0',
                'profit_margin' => 'sometimes|string|min:0|max:100',
                'cash_flow' => 'sometimes|string|min:0',
                'ROI' => 'sometimes|string',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]
        );

        // Returning any errors coming from the validation 
        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validateData->errors()
            ], 401);
        }

        // Getting the validated data
        $validatedData = $validateData->validated();

        // Find the investment opportunity by ID
        $investmentOpportunity = InvestmentOpportunity::where('uuid', $uuid)->first();

        // Checking if the $investmentOpportunity got the required opportunity 
        if (!$investmentOpportunity) {
            return response()->json([
                'status' => false,
                'message' => 'Investment opportunity not found.',
                'uuid' => $uuid
            ], 404);
        }

        // Storing the photo in /OpportunityImages at public folder and getting the path and storing it into $vaildatedData
        if ($request->hasFile('photo')) {

            $existingPhotoPath = $investmentOpportunity->photo;

            if ($existingPhotoPath && str_starts_with($existingPhotoPath, 'OpportunityImages/')) {
                Storage::disk('custom')->delete($existingPhotoPath);
            }

            $photoFile = $request->file('photo');
            $photoPath = $photoFile->store('OpportunityImages', 'custom'); // 'public' disk, in 'photos' directory
            $validatedData['photo'] = $photoPath;
        }

        // Getting the category ID to store it as a foreign key at investment opportunity table 
        $category = Category::where('name', $request->category_name)->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        // Updating the remaining amount according to amount needed entered
        $validatedAmount = $validatedData['amount_needed'];
        $currentAmount = $investmentOpportunity->amount_needed;
        if ($validatedAmount != $currentAmount) {
            $diff = abs($validatedAmount - $currentAmount);
            $investmentOpportunity->remaining_amount += ($validatedAmount > $currentAmount) ? $diff : -$diff;
        }

        // Adding category_id to the validatedData to update it 
        $validatedData['category_id'] = $category->id;
        $validatedData['approved'] = false;

        // Update the investment opportunity with the validated data
        $investmentOpportunity->update($validatedData);

        $investmentOpportunity->approved = false ; 
        // Returining the successfull response to the front end (Data updated successfully!)
        return response()->json([
            'status' => true,
            'message' => 'Investment opportunity updated successfully!',
            'data' => $investmentOpportunity
        ], 200);
    }

    // Getting investment opportunity id to delete it 
    public function delete($uuid)
    {
        // Retrieve the investment opportunity by UUID
        $opportunity = InvestmentOpportunity::where('uuid', $uuid)->first();

        // Check if the investment opportunity exists
        if (!$opportunity) {
            return response()->json([
                'status' => false,
                'message' => 'Investment Opportunity not found!'
            ], 404);
        }

        // Delete the investment opportunity
        $opportunity->delete();

        // Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'Investment Opportunity deleted successfully!'
        ], 200);
    }
}
