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
            $table->enum('logging_unit_type', ['departmental', 'contractual'])->default('departmental')->after('unitNo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->dropColumn('logging_unit_type');
        });
    }
};
