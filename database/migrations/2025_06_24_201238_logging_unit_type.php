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
        Schema::create('loggingUnitType', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('logType_id')->unsigned();
            $table->bigInteger('loggingUnit_id')->unsigned();

            $table->foreign('logType_id')->references('id')->on('logTypes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('loggingUnit_id')->references('id')->on('loggingUnits')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loggingUnitType');
    }
};
