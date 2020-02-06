<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Notifications\Generic;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
    private $paypal;

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'aal_name']);

        $this->paypal = new ApiContext(new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret'))
        );
        $this->paypal->setConfig(config('paypal.settings'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|int|between:5,100'
        ]);

        $name = config('app.name') . ': Add Credits';
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
        return redirect()->away($payment->getApprovalLink());
    }

    public function success(Request $request)
    {
        $paymentID = session('payment_id');
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

            $user->notify(new Generic($user, "You have deposited $$price worth of credits using PayPal."));

            return redirect(RouteServiceProvider::HOME)->with('success', 'Payment success.');
        }

        return redirect(RouteServiceProvider::HOME)->with('error', 'Payment failed.');
    }

    public function cancel()
    {
        return redirect(RouteServiceProvider::HOME)->with('error', 'Payment cancelled.');
    }

}
