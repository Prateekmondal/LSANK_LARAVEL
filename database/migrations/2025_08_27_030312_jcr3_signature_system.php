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
        Schema::table('jcr', function (Blueprint $table) {
        // Signature System
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('creator_signature')->nullable();
            $table->timestamp('creator_signed_at')->nullable();
            
            $table->foreignId('party_chief_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('party_chief_signature')->nullable();
            $table->timestamp('party_chief_signed_at')->nullable();
            
            $table->foreignId('operation_incharge_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('operation_incharge_signature')->nullable();
            $table->timestamp('operation_incharge_signed_at')->nullable();

            // Status
            $table->string('status')->default('draft');
            $table->boolean('final_submit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn(['creator_signature', 'creator_signed_at']);
            $table->dropForeign(['party_chief_id']);
            $table->dropColumn(['party_chief_signature', 'party_chief_signed_at']);
            $table->dropForeign(['operation_incharge_id']);
            $table->dropColumn(['operation_incharge_signature', 'operation_incharge_signed_at']);
            $table->dropColumn(['status', 'final_submit']);
        });
    }
};
