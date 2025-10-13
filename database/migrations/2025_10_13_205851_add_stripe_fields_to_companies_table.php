<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('companies', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('subscription_ends_at');
            }
            if (!Schema::hasColumn('companies', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('companies', 'stripe_price_id')) {
                $table->string('stripe_price_id')->nullable()->after('stripe_subscription_id');
            }
            
            // Rendre admin_id nullable pour éviter les contraintes circulaires
            if (Schema::hasColumn('companies', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'stripe_customer_id')) {
                $table->dropColumn('stripe_customer_id');
            }
            if (Schema::hasColumn('companies', 'stripe_subscription_id')) {
                $table->dropColumn('stripe_subscription_id');
            }
            if (Schema::hasColumn('companies', 'stripe_price_id')) {
                $table->dropColumn('stripe_price_id');
            }
            
            // Remettre admin_id en non-nullable
            if (Schema::hasColumn('companies', 'admin_id')) {
                $table->foreignId('admin_id')->nullable(false)->change();
            }
        });
    }
};