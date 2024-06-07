<?php

namespace App\Http\Controllers\Opportunities;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvestmentOpportunity;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GetPhotoUrlController;

class OpportunityApproveController extends Controller
{
    // Getting All investment opportunities that on waiting list
    public function unApprovedOpportunities()
    {
        // Getting all opportunities that have approved 'false' (on waiting list)
        $nonApprovedOpportunities = InvestmentOpportunity::where('approved', false)
            ->with('category')
            ->get();

        GetPhotoUrlController::transform($nonApprovedOpportunities);

        // Returning the successfull response if $nonApprovedOpportunities not empty else : 'No investment opportunities found'
        return response()->json([
            'status' => true,
            'data' => $nonApprovedOpportunities->isEmpty() ? 'No investment opportunities found' : $nonApprovedOpportunities
        ], 200);
    }

    // Approving the opportunity by the admin
    public function approve($uuid)
    {
        // Getting the opportunity that needed to be approved by the uuid
        $opportunity = InvestmentOpportunity::where('uuid', $uuid)->first();

        // Checking if the id is refering to a opportunity at the database
        if (!$opportunity) {
            return response()->json([
                'status' => false,
                'message' => 'No Investment Opportunity found with that id'
            ], 404);
        }

        // Approve the investment opportunity
        $opportunity->update([
            'admin_id' => auth()->id(),
            'approved' => true
        ]);
        // Returning a successfull message
        return response()->json([
            'status' => true,
            'message' => 'Investment Opportunity approved successfully !',
        ], 200);
    }

    // Rejecting the opportunity by the admin
    public function reject($uuid)
    {
        // Getting the opportunity that needed to be rejected by the uuid
        $opportunity = InvestmentOpportunity::where('uuid', $uuid)->first();

        // Checking if the id is refering to a opportunity at the database
        if (!$opportunity) {
            return response()->json([
                'status' => false,
                'message' => 'No Investment Opportunity found with that id'
            ], 404);
        }

        // Delete the photo file associated with the opportunity if it exists
        if ($opportunity->photo && Storage::disk('custom')->exists($opportunity->photo)) {
            Storage::disk('custom')->delete($opportunity->photo);
        }
        
        $business = Business::where('user_id', $opportunity->business_id)->first();
        $business->update([
            'total_funds_raised' => $business->total_funds_raised - $opportunity->amount_needed
        ]);

        $opportunity->delete();

        return response()->json([
            'status' => true,
            'message' => 'Investment Opportunity disapproved successfully !',
        ], 200);
    }
}
