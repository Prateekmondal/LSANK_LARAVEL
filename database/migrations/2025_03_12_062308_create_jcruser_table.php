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
        Schema::create('jcruser', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jcr_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('jcr_id')->references('id')->on('jcr')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcruser');
    }
};
