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
            $table->unsignedBigInteger('creator_id')->index();
            $table->string('creator_signature')->nullable();
            $table->timestamp('creator_signed_at')->nullable();
            
            $table->unsignedBigInteger('party_chief_id')->nullable()->index();
            $table->string('party_chief_signature')->nullable();
            $table->timestamp('party_chief_signed_at')->nullable();
            
            $table->unsignedBigInteger('operation_incharge_id')->nullable()->index();
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
