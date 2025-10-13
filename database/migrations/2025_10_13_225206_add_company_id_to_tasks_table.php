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
        Schema::table('tasks', function (Blueprint $table) {
            // Ajouter company_id nullable pour permettre les tâches d'utilisateurs indépendants
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['company_id', 'created_at']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
