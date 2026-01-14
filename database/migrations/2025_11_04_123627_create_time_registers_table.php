<?php
// database/migrations/2024_01_01_000001_create_time_registers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRegistersTable extends Migration
{
    public function up()
    {
        Schema::create('time_registers', function (Blueprint $table) {
            $table->id();
            $table->string('logging_unit_no');
            $table->string('indent_no');
            $table->string('well_no');
            $table->string('rig_no');
            
            // Separate date and time fields
            $table->date('well_indented_date');
            $table->time('well_indented_time');
            $table->date('well_taken_up_date')->nullable();
            $table->time('well_taken_up_time')->nullable();
            $table->date('well_handed_over_date')->nullable();
            $table->time('well_handed_over_time')->nullable();
            
            $table->text('job_carried_out');
            $table->text('observations_by_logging_chief');
            
            // Logging Chief details (auto-captured from logged-in user)
            $table->foreignId('logging_chief_id')->constrained('users');
            $table->string('logging_chief_name');
            $table->string('logging_chief_designation');
            $table->text('logging_chief_signature')->nullable();
            $table->timestamp('logging_chief_signed_at')->nullable();
            
            // Rig Representative Information
            $table->string('rig_representative_email')->nullable();
            $table->text('rig_representative_observations')->nullable();
            $table->text('rig_representative_signature')->nullable();
            $table->string('rig_representative_name')->nullable();
            $table->string('rig_representative_designation')->nullable();
            $table->timestamp('rig_representative_signed_at')->nullable();
            
            // Status and Workflow
            $table->enum('status', ['draft', 'preview', 'pending_signature', 'completed'])->default('draft');
            $table->string('signature_token')->unique()->nullable();
            $table->boolean('is_final_submitted')->default(false);
            $table->timestamp('final_submitted_at')->nullable();
            
            // Creator
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_registers');
    }
}