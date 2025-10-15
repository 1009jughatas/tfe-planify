<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corriger les incohérences entre plan et prix
        $companies = DB::table('companies')->get();
        
        foreach ($companies as $company) {
            $plan = $company->plan;
            $price = $company->monthly_price;
            
            // Déterminer le bon plan selon le prix
            if ($price == 399.00 && $plan !== 'starter') {
                DB::table('companies')->where('id', $company->id)->update([
                    'plan' => 'starter',
                    'max_users' => 10
                ]);
            } elseif ($price == 599.00 && $plan !== 'growth') {
                DB::table('companies')->where('id', $company->id)->update([
                    'plan' => 'growth',
                    'max_users' => 20
                ]);
            } elseif ($price == 999.00 && $plan !== 'enterprise') {
                DB::table('companies')->where('id', $company->id)->update([
                    'plan' => 'enterprise',
                    'max_users' => 999999
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de rollback nécessaire pour cette correction
    }
};