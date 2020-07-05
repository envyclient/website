<?php

namespace App\Http\Controllers;

use App\BillingAgreement;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Common\PayPalModel;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
    private $paypal;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('admin')->only('createBillingPlan');

        $this->paypal = new ApiContext(new OAuthTokenCredential(
            config('paypal.client_id'),
            config('paypal.secret')
        ));
        $this->paypal->setConfig(config('paypal.settings'));
    }

    public function createBillingPlan()
    {
        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('Standard')
            ->setDescription('1 month of recurring Envy Client')
            ->setType('INFINITE');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('MONTH')
            ->setFrequencyInterval('1')
            ->setAmount(new Currency([
                'value' => 7,
                'currency' => 'USD'
            ]));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('https://dashboard.envyclient.com/paypal/execute')
            ->setCancelUrl('https://dashboard.envyclient.com/paypal/cancel')
            ->setAutoBillAmount('YES')
            ->setInitialFailAmountAction('CANCEL')
            ->setMaxFailAttempts('0')
            ->setSetupFee(new Currency([
                'value' => 7,
                'currency' => 'USD'
            ]));

        $plan->setPaymentDefinitions([$paymentDefinition]);
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $createdPlan = $plan->create($this->paypal);
        } catch (PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        }

        $patch = new Patch();
        $patch->setOp('replace')
            ->setPath('/')
            ->setValue(new PayPalModel('{"state":"ACTIVE"}'));

        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);
        $createdPlan->update($patchRequest, $this->paypal);

        $plan = Plan::get($createdPlan->getId(), $this->paypal);
        dd($plan);
    }

    public function process(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $user = $request->user();
        if ($user->hasBillingAgreement()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Base Agreement')
            ->setDescription('Basic Agreement')
            ->setStartDate(Carbon::today()->addMonth()->toIso8601String());

        // Set plan id
        $plan = new Plan();
        $plan->setId(\App\Plan::find($request->id)->paypal_id);
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            $agreement = $agreement->create($this->paypal);
        } catch (Exception $e) {
            die($e);
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        // storing the plan id so we can retrieve it when the user returns
        session([
            'plan_id' => $request->id
        ]);

        return redirect()->away($agreement->getApprovalLink());
    }

    public function execute(Request $request)
    {
        // getting the plan id from the session
        $planId = session('plan_id');
        session()->forget('plan_id');

        if (!$request->has('token')) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        $user = $request->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        $agreement = new Agreement();

        try {
            $result = $agreement->execute($request->token, $this->paypal);
            //dd($result);
        } catch (Exception $e) {
            die($e);
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        if ($agreement->getState() !== 'Active') {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        $billingAgreement = new BillingAgreement();
        $billingAgreement->billing_agreement_id = $agreement->getId();
        $billingAgreement->user_id = $request->user()->id;
        $billingAgreement->plan_id = $planId;
        $billingAgreement->state = $agreement->getState();
        $billingAgreement->save();

        return redirect(RouteServiceProvider::HOME)->with('success', 'Subscription successful.');
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription cancelled.');
    }

}
