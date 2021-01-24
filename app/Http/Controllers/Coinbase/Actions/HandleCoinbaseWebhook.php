<?php

namespace App\Http\Controllers\Coinbase\Actions;

use App\Http\Controllers\Controller;
use App\Models\Coinbase;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandleCoinbaseWebhook extends Controller
{
    public function __construct()
    {
        $this->middleware('valid-json-payload');
    }

    public function __invoke(Request $request)
    {
        if (!self::verifySignature($request->getContent(),
            $request->header('x-cc-webhook-signature'),
            'ba6a5abd-07fb-4779-9e61-0c5394ff3645')) {
            return response()->json([
                'message' => 'Invalid Signature',
            ], 400);
        }

        Log::debug($request->getContent());

        switch ($request->json('event.type')) {
            case 'charge:confirmed':
            {
                $coinbase = Coinbase::where('charge_id', $request->json('event.data.id'))
                    ->firstOrFail();

                // create subscription for the user
                Subscription::create([
                    'user_id' => $coinbase->user_id,
                    'plan_id' => $coinbase->plan_id,
                    'billing_agreement_id' => null,
                    'end_date' => now()->addMonth(),
                ]);

                self::updateStatus($request->json('event.data.id'), 'charge:confirmed');
                break;
            }
            case 'charge:failed':
            case 'charge:pending':
            case 'charge:delayed':
            case 'charge:resolved':
            {
                self::updateStatus($request->json('event.data.id'), $request->json('event.type'));
                break;
            }
        }

        return response()->json([
            'message' => '200 OK',
        ]);
    }

    private static function updateStatus(string $id, string $status)
    {
        Coinbase::where('charge_id', $id)->update([
            'status' => $status
        ]);
    }

    private static function verifySignature($payload, $sigHeader, $secret): bool
    {
        $computedSignature = hash_hmac('sha256', $payload, $secret);
        return self::hashEqual($sigHeader, $computedSignature);
    }

    private static function hashEqual($str1, $str2): bool
    {
        if (function_exists('hash_equals')) {
            return \hash_equals($str1, $str2);
        }

        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}
