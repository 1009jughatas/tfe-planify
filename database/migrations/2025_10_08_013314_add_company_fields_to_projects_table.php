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
        Schema::table('projects', function (Blueprint $table) {
            // Cloisonnement par entreprise
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            
            // Index pour optimiser les requêtes
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['company_id', 'created_at']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};