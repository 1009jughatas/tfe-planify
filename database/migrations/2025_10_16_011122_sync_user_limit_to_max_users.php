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
        // Synchroniser les données de user_limit vers max_users si nécessaire
        DB::statement('UPDATE companies SET max_users = user_limit WHERE max_users != user_limit OR max_users IS NULL');
        
        // Supprimer la colonne user_limit qui n'est plus utilisée
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('user_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la colonne user_limit
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('user_limit')->nullable()->after('max_users');
        });
        
        // Synchroniser max_users vers user_limit
        DB::statement('UPDATE companies SET user_limit = max_users');
    }
};