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
            // Rendre company_id nullable pour permettre les projets d'utilisateurs indépendants
            $table->foreignId('company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Remettre company_id comme obligatoire (attention: cela peut causer des erreurs si des projets ont company_id = null)
            $table->foreignId('company_id')->nullable(false)->change();
        });
    }
};
