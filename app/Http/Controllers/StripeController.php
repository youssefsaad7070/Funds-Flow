<?php

namespace App\Http\Controllers;

use App\Models\User;
use Stripe\StripeClient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\InvestmentOpportunity;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
    public function session(Request $request)
    {

        $user = User::find(auth()->id());

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => true,
                'message' => 'Sorry, You must verify your email address first!.',
            ], 403);
        } else if ($user->is_approved == false) {
            return response()->json([
                'status' => true,
                'message' => 'Sorry, You are not approved yet, Please contact us!.',
            ], 403);
        }

        $input = $request->only(['amount', 'uuid']);

        $validator = Validator::make($input, [
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value < 1000) {
                        return $fail('The amount must be at least 1000 EGP.');
                    }
                },
            ],
            'uuid' => 'required|exists:investment_opportunities,uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $opportunity = InvestmentOpportunity::select('id', 'business_id', 'business_name', 'remaining_amount')
            ->where('uuid', $request->uuid)
            ->first();

        if (!$opportunity) {
            return response()->json([
                'status' => false,
                'message' => 'No Investment Opportunity found for that id!'
            ], 404);
        }
        $price = $request->amount;

        if ($opportunity->remaining_amount < $price && $opportunity->remaining_amount != 0) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot exceed the remaining amount of the investment opportunity!.'
            ], 400);
        } else if ($opportunity->remaining_amount == 0) {
            return response()->json([
                'status' => false,
                'message' => 'This investment opportunity has ended.',
            ], 410);
        }
        $successUrl = $request->successUrl . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = $request->cancelUrl ;
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = $price * 100;

        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency' => 'EGP',
                        'product_data' => [
                            "name" => $opportunity->business_name,
                        ],
                        'unit_amount'  => $amount,
                    ],
                    'quantity'   => 1,
                ],

            ],
            'mode'        => 'payment',
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
            'metadata' => [
                'business_id' => $opportunity->business_id,
                'business_name' => $opportunity->business_name,
                'opportunity_id' => $opportunity->id,
                'amount' => $price,
                'investor_id' => auth()->id(),
            ],
        ]);

        return response()->json([
            'status' => true,
            'data' => $session->url,
            'fullData' => $session
        ]);
    }

    public function getSessionDetails($sessionId)
    {
        // Fetch the transaction details using the Stripe session ID
        $transaction = Transaction::where('stripe_id', $sessionId)->first();

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'No transaction found for that session ID!'
            ], 404);
        }

        $transaction->makeHidden('investmentOpportunity', 'investor');

        GetPhotoUrlController::transformOne($transaction->investmentOpportunity);

        return response()->json([
            'status' => true,
            'transaction' => $transaction,
            'investmentOpportunity' => $transaction->investmentOpportunity,
            'investor' => $transaction->investor,
        ]);
    }
}
