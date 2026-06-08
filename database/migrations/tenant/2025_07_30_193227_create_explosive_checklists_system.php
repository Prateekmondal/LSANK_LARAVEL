<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('explosive_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jcr_id')->nullable();
            $table->enum('type', ['a', 'b', 'c']);
            $table->string('well_no');
            $table->string('rig')->nullable();
            $table->string('logging_unit_no');
            $table->string('job_type')->nullable();
            $table->string('perf_interval')->nullable();
            $table->date('date');
            $table->json('checklist_data');
            $table->enum('status', ['draft', 'completed', 'signed'])->default('draft');
            $table->unsignedBigInteger('creator_id')->index();
            $table->enum('sign_status', ['pending', 'partially_signed', 'fully_signed'])->default('pending');
            $table->timestamps();
            $table->foreign('jcr_id')->references('id')->on('jcr')->onDelete('cascade');
        });

        Schema::create('checklist_forwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explosive_checklist_id')->constrained('explosive_checklists');
            $table->unsignedBigInteger('from_user_id')->index();
            $table->unsignedBigInteger('to_user_id')->index();
            $table->text('message')->nullable();
            $table->string('purpose')->default('review');
            $table->text('comments')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->timestamps();
        });

        Schema::create('checklist_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explosive_checklist_id')->constrained('explosive_checklists');
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('signature_type', ['creator', 'approver']);
            $table->timestamp('signed_at');
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checklist_signatures');
        Schema::dropIfExists('checklist_forwards');
        Schema::dropIfExists('explosive_checklists');
        Schema::dropIfExists('notifications');
    }
};