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
        // Mettre à jour les plans existants vers le nouveau format
        DB::table('companies')->where('plan', 'professional')->update([
            'plan' => 'growth',
            'monthly_price' => 599.00,
            'max_users' => 20
        ]);
        
        DB::table('companies')->where('plan', 'starter')->update([
            'plan' => 'starter',
            'monthly_price' => 399.00,
            'max_users' => 10
        ]);
        
        DB::table('companies')->where('plan', 'enterprise')->update([
            'plan' => 'enterprise',
            'monthly_price' => 999.00,
            'max_users' => null
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux anciens plans si nécessaire
        DB::table('companies')->where('plan', 'growth')->update([
            'plan' => 'professional'
        ]);
    }
};