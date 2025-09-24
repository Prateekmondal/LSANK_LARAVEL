<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('external_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explosive_checklist_id')->constrained('explosive_checklists');
            $table->string('name');
            $table->string('designation');
            $table->string('cpf_no');
            $table->string('email');
            $table->timestamp('signed_at');
            $table->timestamps();
        });

        Schema::table('explosive_checklists', function (Blueprint $table) {
            $table->enum('external_sign_status', ['pending', 'sent', 'completed'])->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('external_signatures');
        Schema::table('explosive_checklists', function (Blueprint $table) {
            $table->dropColumn('external_sign_status');
        });
    }
};