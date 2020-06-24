<?php

namespace App\Http\Controllers\API;

use App\BillingAgreement;
use App\Http\Controllers\Controller;
use App\Subscription;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandlePayPalWebhook extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        // TODO: handle webhook
        if (!$request->has('event_type')) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        switch (strtolower($request->event_type)) {
            case 'PAYMENT.SALE.COMPLETED': // received payment so we extend or create subscription
            {
                // get the user that this billing id belongs to
                $billingAgreement = BillingAgreement::where('billing_agreement_id', $request->resource->billing_agreement_id)->firstOrFail();
                $user = $billingAgreement->user;

                // TODO: handle plan switching

                if ($user->hasSubscription()) {
                    $user->subscription->end_date = $user->subscription->end_date->addDays(30);
                    $user->subscription->save();
                } else {
                    $subscription = new Subscription();
                    $subscription->user_id = $user->id;
                    $subscription->plan_id = $billingAgreement->plan_id;
                    $subscription->end_date = Carbon::now()->addDays(30);
                    $subscription->save();
                }

                break;
            }
            case 'BILLING.SUBSCRIPTION.CANCELLED': // user has cancelled their subscription
            {
                break;
            }
        }

    }
}
