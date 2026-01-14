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
        Schema::create('logsRecorded', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jcr_id')->unsigned();
            $table->integer('runNo');
            $table->string('logRecorded');
            $table->float('bottomDepth');
            $table->float('topDepth');
            $table->string('toolNo')->nullable();
            $table->string('logQuality');
            $table->float('bottomShotDepth')->nullable();
            $table->float('topShotDepth')->nullable();
            $table->string('charge')->nullable();
            $table->integer('chargeNo')->nullable();
            $table->string('primaChord')->nullable();
            $table->float('primaChordQty')->nullable();
            $table->string('fuse')->nullable();
            $table->integer('fuseNo')->nullable();
            $table->string('fMf')->nullable();

            $table->foreign('jcr_id')->references('id')->on('jcr')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logsRecorded');
    }
};
