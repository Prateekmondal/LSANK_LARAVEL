<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSapFieldsToJcrsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->string('sap_document_number')->nullable()->unique()->after('time_register_linked');
            $table->timestamp('sap_pushed_at')->nullable()->after('sap_document_number');
            $table->string('sap_status')->default('pending')->after('sap_pushed_at')->comment('pending, pushed, failed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->dropColumn(['sap_document_number', 'sap_pushed_at', 'sap_status']);
        });
    }
}
