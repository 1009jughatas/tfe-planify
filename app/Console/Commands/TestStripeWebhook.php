<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Services\StripeService;

class TestStripeWebhook extends Command
{
    protected $signature = 'stripe:test-webhook {company_id}';
    protected $description = 'Tester les webhooks Stripe pour une entreprise';

    public function handle()
    {
        $companyId = $this->argument('company_id');
        $company = Company::find($companyId);
        
        if (!$company) {
            $this->error("Entreprise avec l'ID {$companyId} non trouvée.");
            return;
        }

        $this->info("Test des webhooks pour l'entreprise: {$company->name}");
        $this->info("Plan actuel: {$company->plan}");
        $this->info("Prix: {$company->monthly_price}€");
        $this->info("Statut: {$company->status}");
        
        if ($company->stripe_customer_id) {
            $this->info("Customer ID: {$company->stripe_customer_id}");
        } else {
            $this->warn("Aucun Customer ID Stripe configuré");
        }
        
        if ($company->stripe_subscription_id) {
            $this->info("Subscription ID: {$company->stripe_subscription_id}");
        } else {
            $this->warn("Aucun Subscription ID Stripe configuré");
        }

        // Tester la création d'un abonnement Stripe
        if (!$company->stripe_customer_id) {
            $this->info("Création d'un client Stripe...");
            try {
                $stripeService = new StripeService();
                $customer = $stripeService->createCustomer([
                    'email' => $company->email,
                    'name' => $company->name,
                    'company_name' => $company->name,
                ]);
                
                $company->update(['stripe_customer_id' => $customer->id]);
                $this->info("Client Stripe créé: {$customer->id}");
                
            } catch (\Exception $e) {
                $this->error("Erreur lors de la création du client: " . $e->getMessage());
            }
        }
    }
}