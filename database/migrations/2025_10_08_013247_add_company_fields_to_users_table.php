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
        Schema::table('users', function (Blueprint $table) {
            // Relation avec l'entreprise
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            
            // Rôles mis à jour pour le SaaS B2B
            $table->enum('role', ['super_admin', 'company_admin', 'member'])->default('member')->change();
            
            // Supprimer is_premium (remplacé par le système d'entreprise)
            $table->dropColumn('is_premium');
            
            // Ajouter des champs pour le profil utilisateur
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->string('role')->default('user')->change();
            $table->boolean('is_premium')->default(false);
            $table->dropColumn(['position', 'department', 'is_active']);
        });
    }
};