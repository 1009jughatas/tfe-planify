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
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $checkoutSession = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur', // Changé en euros
                            'product_data' => [
                                'name' => 'Plan Premium Planify',
                                'description' => 'Accès complet à toutes les fonctionnalités Premium',
                            ],
                            'unit_amount' => 9900, // 99€ en centimes
                            'recurring' => [
                                'interval' => 'month',
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'subscription', // Changé en mode subscription pour l'abonnement mensuel
                'success_url' => route('premium.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('premium.show'),
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'user_id' => Auth::user()->id,
                ],
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            return redirect()->route('premium.show')
                ->with('error', 'Une erreur est survenue lors de la création de la session de paiement: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                return redirect()->route('premium.show')
                    ->with('error', 'Session de paiement introuvable.');
            }

            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = CheckoutSession::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $user = Auth::user();
                $user->update([
                    'is_premium' => true,
                    'stripe_customer_id' => $session->customer,
                    'stripe_subscription_id' => $session->subscription,
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Félicitations ! Vous avez maintenant accès à toutes les fonctionnalités Premium.');
            } else {
                return redirect()->route('premium.show')
                    ->with('error', 'Le paiement n\'a pas été finalisé.');
            }
        } catch (\Exception $e) {
            return redirect()->route('premium.show')
                ->with('error', 'Une erreur est survenue lors de la vérification du paiement: ' . $e->getMessage());
        }
    }
}