<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;

use App\Ads;
use App\Http\Resources\AdsResource;

class FeatureController extends Controller
{
    public function store(Ads $ad, Request $request)
    {
        if (Auth::user()->role === 2 || Auth::user()->id != $ad->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $payment = Mollie::api()
            ->payments()
            ->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => '10.00'
                ],
                'description' => 'My first API payment',
                'webhookUrl' => route('webhooks.mollie'),
                // 'redirectUrl' =>
                "metadata" => [
                    "ad_id" => $ad->id
                ]
            ]);

        $payment = Mollie::api()
            ->payments()
            ->get($payment->id);

        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }
}
