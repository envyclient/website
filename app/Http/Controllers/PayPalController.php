<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Role;
use Carbon\Carbon;
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
        $this->paypal = new ApiContext(new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret'))
        );
        $this->paypal->setConfig(config('paypal.settings'));
        $this->middleware('auth');
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->hasPurchased()) {
            return back()->with('error', 'You already own the client.');
        }

        $price = 20.0;
        $name = config('app.name');

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
            return redirect('/')->with('error', 'Some error occurred, please try again later.');
        }

        session(['payment_id' => $payment->getId()]);
        return redirect()->away($payment->getApprovalLink());
    }

    public function success(Request $request)
    {
        $paymentID = session('payment_id');
        session()->forget('payment_id');

        if (!$request->has('PayerID') || !$request->has('token')) {
            return redirect('/')->with('error', 'Payment failed.');
        }

        $token = $request->token;
        $payerID = $request->PayerID;

        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);

        try {
            $result = Payment::get($paymentID, $this->paypal)->execute($execution, $this->paypal);
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Payment failed.');
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

            $user->role_id = Role::PREMIUM[0];
            $user->save();

            return redirect('/')->with('success', 'Payment success.');
        }

        return redirect('/')->with('error', 'Payment failed.');
    }

    public function cancel()
    {
        return redirect('/')->with('error', 'Payment cancelled.');
    }

}
