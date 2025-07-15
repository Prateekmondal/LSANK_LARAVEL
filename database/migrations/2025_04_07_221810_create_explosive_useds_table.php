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
        Schema::create('explosiveUsed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jcr_id')->nullable();
            $table->string('explosive')->nullable();
            $table->float('issued')->nullable();
            $table->float('used')->nullable();
            $table->float('returned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explosiveUsed');
    }
};
