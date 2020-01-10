<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ads;
use Carbon\Carbon;

class MollieWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            if (!$request->has('id')) {
                return;
            }

            $payment = Mollie::api()
                ->payments()
                ->get($request->id);

            if (
                $payment->isPaid() &&
                !$payment->hasRefunds() &&
                !$payment->hasChargebacks()
            ) {
                $ad = Ads::findOrFail($payment->metadata->ad_id);

                $ad->update([
                    'featuredAt' => Carbon::now()->toDateTimeString()
                ]);
            }
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
    }
}
