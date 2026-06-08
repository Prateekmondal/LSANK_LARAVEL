<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/xxxx_create_audit_logs_table.php
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event'); // 'created', 'updated', 'deleted', 'restored', etc.
            $table->morphs('auditable'); // Polymorphic: model_type + model_id
            $table->json('old_values')->nullable(); // Before changes (for updates)
            $table->json('new_values')->nullable(); // After changes
            $table->string('url')->nullable(); // Which endpoint triggered this?
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();

            $table->index(['event', 'auditable_type', 'auditable_id']); // Faster queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
