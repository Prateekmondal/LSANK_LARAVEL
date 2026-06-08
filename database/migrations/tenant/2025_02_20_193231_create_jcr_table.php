<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private $timestamps = true;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jcr', function (Blueprint $table) {
            $table->id();
            $table->string('fieldName', 20);
            $table->string('wellNo');
            $table->date('jobDate');
            $table->integer('jobNo');
            $table->date('workOrderDate');
            $table->string('indentNo');
            $table->string('rigNo');
            $table->float('kb')->nullable();
            $table->float('gl')->nullable();
            $table->string('unitNo');
            $table->string('loggingType');
            $table->string('logType');
            $table->string('wellOwner');
            $table->string('mastVanNo')->nullable();
            $table->string('lvNo');
            $table->string('wellType');
            $table->string('rigType');
            $table->date('assembled_date');
            $table->time('assembled_time');
            $table->date('depOffice_date');
            $table->time('depOffice_time');
            $table->date('arrivalSite_date');
            $table->time('arrivalSite_time');
            $table->date('indented_date');
            $table->time('indented_time');
            $table->date('wellReadiness_date');
            $table->time('wellReadiness_time');
            $table->date('wellTaken_date');
            $table->time('wellTaken_time');
            $table->date('rigUP_date');
            $table->time('rigUP_time');
            $table->date('wellHandOver_date');
            $table->time('wellHandOver_time');
            $table->date('depSite_date');
            $table->time('depSite_time');
            $table->date('arrivalOffice_date');
            $table->time('arrivalOffice_time');
            $table->float('preparationTime');
            $table->float('postProceTime');
            $table->string('depthDriller')->nullable();
            $table->string('depthLogger')->nullable();
            $table->string('casingSize')->nullable();
            $table->string('casingShoeDriller')->nullable();
            $table->string('casingShoeLogger')->nullable();
            $table->string('floatCollar')->nullable();
            $table->string('bitSize')->nullable();
            $table->string('tubingSize')->nullable();
            $table->string('t_shoe_Packer')->nullable();
            $table->string('s_nippletopexp')->nullable();
            $table->string('THP')->nullable();
            $table->string('maxDevAt')->nullable();
            $table->string('distTo_FroKms')->nullable();
            $table->string('rm')->nullable();
            $table->string('rmtemp')->nullable();
            $table->string('rmf')->nullable();
            $table->string('rmftemp')->nullable();
            $table->string('rmc')->nullable();
            $table->string('rmctemp')->nullable();
            $table->string('bht')->nullable();
            $table->string('bhtdepth')->nullable();
            $table->string('spgr')->nullable();
            $table->string('viscosity')->nullable();
            $table->string('mudType')->nullable();
            $table->string('waterloss')->nullable();
            $table->string('ph')->nullable();
            $table->string('oilpercnt')->nullable();
            $table->string('kcl_barytes')->nullable();
            $table->string('salinity')->nullable();
            $table->string('lastcirc_from')->nullable();
            $table->string('lastcirc_to')->nullable();
            $table->string('cableSize');
            $table->string('insulation');
            $table->date('shoeDate');
            $table->string('weakPoint');
            $table->string('cableHeadSize');
            $table->string('cableLength');
            $table->string('initialLength');
            $table->string('surfaceEquipment');
            $table->string('automobile');
            $table->string('wellCondition');
            $table->string('timeLoss');
            $table->integer('attempted')->nullable();
            $table->integer('recovered')->nullable();
            $table->integer('missFire')->nullable();
            $table->integer('barrelLost')->nullable();
            $table->integer('emptyBarrel')->nullable();
            $table->integer('chargeUsed')->nullable();
            $table->string('permitType');
            $table->string('permitNo');
            $table->integer('permitWork');
            $table->integer('elecLockout')->nullable();
            $table->string('elecLockoutNo')->nullable();
            $table->integer('safetyMeeting');
            $table->integer('jobCloseMeeting');
            $table->integer('nearMiss');
            $table->text('nearMissDesc')->nullable();
            $table->text('jobStatus');
            $table->text('remarks');
            $table->text('objective')->nullable();
            $table->text('observations')->nullable();
            $table->text('contingents')->nullable();
            $table->timestamps(); // Adds nullable created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jcr');
    }
};
