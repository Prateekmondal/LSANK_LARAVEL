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
            $table->boolean('party_chief_edited')->default(false)->after('final_submit');
            $table->boolean('operation_incharge_edited')->default(false)->after('party_chief_edited');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->dropColumn(['party_chief_edited', 'operation_incharge_edited']);
        });
    }
};