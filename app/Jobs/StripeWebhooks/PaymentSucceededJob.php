<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\InvestmentOpportunity;
use App\Models\Investor;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;

class PaymentSucceededJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = $this->webhookCall->payload['data']['object'];

        $user = User::where('id', $payment['metadata']['investor_id'])->first();
        $opportunity_id = $payment['metadata']['opportunity_id'] ?? null;
        $business_id = $payment['metadata']['business_id'] ?? null;
        $amount = $payment['metadata']['amount'] ?? null;

        if ($user) {
            Transaction::create([
                'investor_id' => $user->id,
                'opportunity_id' => $opportunity_id,
                'business_id' => $business_id,
                'amount' => $amount,
                'stripe_id' => $payment['id'],
            ]);
            $opportunity = InvestmentOpportunity::find($opportunity_id);

            $remainingAmountAfterPayment = ($opportunity->remaining_amount) - ($amount);
            $remainingAmountUpdated = max(0, $remainingAmountAfterPayment);

            $opportunity->update([
                'remaining_amount' => $remainingAmountUpdated,
            ]);
            
            if ( $remainingAmountUpdated == 0){
                $opportunity->update([
                    'active' => false
                ]);
            }

            if($user->hasRole('investor')){
                $investor = Investor::where('user_id', $user->id)->first();

                $investor->update([
                    'total_invested' => ($investor->total_invested ?? 0) + $amount
                ]);
            }
        }
    }
}
