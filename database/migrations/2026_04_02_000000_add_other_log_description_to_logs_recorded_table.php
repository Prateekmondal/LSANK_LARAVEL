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
        Schema::table('logsRecorded', function (Blueprint $table) {
            $table->string('otherLogDescription')->nullable()->after('logRecorded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logsRecorded', function (Blueprint $table) {
            $table->dropColumn('otherLogDescription');
        });
    }
};
