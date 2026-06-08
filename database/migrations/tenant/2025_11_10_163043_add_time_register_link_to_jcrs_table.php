<?php
// Add to existing jcrs migration or create new migration

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeRegisterLinkToJcrsTable extends Migration
{
    public function up()
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->foreignId('time_register_id')->nullable()->constrained('time_registers')->unique()->onDelete('cascade');
            $table->boolean('time_register_linked')->default(false);
        });
    }

    public function down()
    {
        Schema::table('jcr', function (Blueprint $table) {
            $table->dropForeign(['time_register_id']);
            $table->dropColumn(['time_register_id', 'time_register_linked']);
        });
    }
}