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
        Schema::table('company_invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('invited_by')->nullable()->after('accepted_by');
            $table->string('position')->nullable()->after('role');
            $table->string('department')->nullable()->after('position');
            $table->string('status')->default('pending')->after('department');
            
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_invitations', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropColumn(['invited_by', 'position', 'department', 'status']);
        });
    }
};
