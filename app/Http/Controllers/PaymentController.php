<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as CheckoutSession;

class PaymentController extends Controller
{
    public function show()
    {
        return view('premium.show');
    }

    public function purchase(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkoutSession = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Premium Access',
                        ],
                        'unit_amount' => 1000,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('premium.success'),
            'cancel_url' => route('premium.show'),
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $user = Auth::user();
        $user->update(['is_premium' => true]);

        return redirect()->route('dashboard')->with('success', 'You have successfully upgraded to Premium!');
    }
}