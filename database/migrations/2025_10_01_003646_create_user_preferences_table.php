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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->boolean('dark_mode')->default(false);
            $table->string('theme_color')->default('#3b82f6'); // Bleu par défaut
            $table->string('language')->default('fr');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('task_reminders')->default(true);
            $table->integer('reminder_hours_before')->default(24); // Rappel 24h avant
            $table->string('date_format')->default('d/m/Y');
            $table->string('timezone')->default('Europe/Brussels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
