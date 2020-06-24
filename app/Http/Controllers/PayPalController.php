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
        $plan->setName('Premium')
            ->setDescription('1 month of recurring Envy Client')
            ->setType('INFINITE');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('MONTH')
            ->setFrequencyInterval('1')
            //->setCycles('12')
            ->setAmount(new Currency([
                'value' => 10,
                'currency' => 'USD'
            ]));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(route('paypal.executeBillingAgreement'))
            ->setCancelUrl(route('paypal.cancel'))
            ->setAutoBillAmount('YES')
            ->setInitialFailAmountAction('CANCEL')
            ->setMaxFailAttempts('0')
            ->setSetupFee(new Currency([
                'value' => 10,
                'currency' => 'USD'
            ]));

        $plan->setPaymentDefinitions([$paymentDefinition]);
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $createdPlan = $plan->create($this->paypal);
        } catch (Exception $ex) {
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

    public function processAgreement(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        // 'P-2XF851689H528853W3UZLKOA'

        $user = $request->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('Base Agreement')
            ->setDescription('Basic Agreement')
            ->setStartDate(Carbon::now()->addMinutes(1)->toIso8601String());

        // Set plan id
        $plan = new Plan();
        $plan->setId($request->id);
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
        session('plan_id', $request->id);

        return redirect()->away($agreement->getApprovalLink());
    }

    public function executeBillingAgreement(Request $request)
    {
        if (!$request->has('token')) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription failed.');
        }

        $user = $request->user();
        if ($user->hasSubscription()) {
            return back()->with('error', 'You already have a active subscription.');
        }

        // getting the plan id from the session
        $planId = session('plan_id');
        session()->forget('plan_id');

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
        $billingAgreement->save();

        return redirect(RouteServiceProvider::HOME)->with('success', 'Subscription successful.');
    }

    public function create(Request $request)
    {
        /*$this->validate($request, [
            'amount' => 'required|integer|between:5,100'
        ]);

        $name = config('app.name') . ': Credits';
        $price = $request->amount;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($name)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($name)
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
            ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->paypal);
        } catch (Exception $e) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Some error occurred, please try again later.');
        }

        session([
            'payment_id' => $payment->getId(),
            'price' => $price
        ]);
        return redirect()->away($payment->getApprovalLink());*/
    }

    public function success(Request $request)
    {
        /*$paymentID = session('payment_id');
        $price = session('price');
        session()->forget(['payment_id', 'price']);

        if (!$request->has('PayerID') || !$request->has('token')) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Payment failed.');
        }

        $token = $request->token;
        $payerID = $request->PayerID;

        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);

        try {
            $result = Payment::get($paymentID, $this->paypal)->execute($execution, $this->paypal);
        } catch (Exception $e) {
            return redirect(RouteServiceProvider::HOME)->with('error', 'Payment failed.');
        }

        if ($result->getState() == 'approved') {

            $user = auth()->user();

            $invoice = new Invoice();
            $invoice->token = $token;
            $invoice->payer_id = $payerID;
            $invoice->payment_id = $paymentID;
            $invoice->invoice_id = $result->getTransactions()[0]->getInvoiceNumber();
            $invoice->user_id = $user->id;
            $invoice->save();

            $user->deposit($price, 'deposit', ['invoice_id' => $invoice->id, 'description' => "Deposit of $price credits using PayPal."]);

            $user->notify(new Generic($user, "You have deposited $$price worth of credits using PayPal.", 'Deposit'));

            return redirect(RouteServiceProvider::HOME)->with('success', 'Payment success.');
        }

        return redirect(RouteServiceProvider::HOME)->with('error', 'Payment failed.');*/
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::HOME)->with('error', 'Subscription cancelled.');
    }

}
