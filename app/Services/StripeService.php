<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret_key'));
    }

    /**
     * Créer une session de checkout Stripe
     */
    public function createCheckoutSession(array $data)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => "Abonnement Planify - {$data['plan_name']}",
                                'description' => "Plan {$data['plan_name']} pour {$data['company_name']}",
                                'metadata' => [
                                    'company_name' => $data['company_name'],
                                    'plan' => $data['plan'],
                                ],
                            ],
                            'unit_amount' => $data['amount'] * 100, // Stripe utilise les centimes
                            'recurring' => [
                                'interval' => 'month',
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => route('entreprise.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('entreprise.payment.failed'),
                'customer_email' => $data['company_email'],
                'metadata' => [
                    'company_name' => $data['company_name'],
                    'admin_name' => $data['admin_name'],
                    'admin_email' => $data['admin_email'],
                    'plan' => $data['plan'],
                    'session_data' => base64_encode(json_encode($data['session_data'])),
                ],
                'billing_address_collection' => 'required',
                'tax_id_collection' => [
                    'enabled' => true,
                ],
            ]);

            Log::info('Session Stripe créée', ['session_id' => $session->id]);
            return $session;

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors de la création de la session', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Récupérer une session de checkout
     */
    public function retrieveSession(string $sessionId)
    {
        try {
            return Session::retrieve($sessionId);
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la récupération de la session Stripe', [
                'session_id' => $sessionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Vérifier si une session est payée
     */
    public function isSessionPaid(string $sessionId)
    {
        $session = $this->retrieveSession($sessionId);
        return $session->payment_status === 'paid';
    }

    /**
     * Récupérer les métadonnées d'une session
     */
    public function getSessionMetadata(string $sessionId)
    {
        $session = $this->retrieveSession($sessionId);
        return $session->metadata ?? [];
    }

    /**
     * Créer un portail client Stripe
     */
    public function createCustomerPortalSession(string $customerId, string $returnUrl)
    {
        try {
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);

            return $session;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la création du portail client', [
                'customer_id' => $customerId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Créer un client Stripe
     */
    public function createCustomer(array $data)
    {
        try {
            $customer = \Stripe\Customer::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'metadata' => [
                    'company_name' => $data['company_name'] ?? '',
                    'user_id' => $data['user_id'] ?? '',
                ],
            ]);

            return $customer;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la création du client Stripe', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
