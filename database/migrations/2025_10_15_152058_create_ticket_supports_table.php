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
        Schema::create('ticket_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('objet');
            $table->text('description');
            $table->enum('statut', ['ouvert', 'ferme'])->default('ouvert');
            $table->text('reponse')->nullable();
            $table->foreignId('repond_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('repond_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_supports');
    }
};