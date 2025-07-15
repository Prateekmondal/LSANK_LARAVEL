<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

class Jcr extends Model
{
    use HasPermissions;
    public $timestamps = false;

    protected $table = 'jcr';

    protected $fillable = [
        'fieldName',
        'wellNo',
        'jobDate',
        'jobNo',
        'workOrderDate',
        'indentNo',
        'rigNo',
        'kb',
        'gl',
        'unitNo',
        'loggingType',
        'logType',
        'wellOwner',
        'mastVanNo',
        'lvNo',
        'wellType',
        'rigType',
        'assembled_date',
        'assembled_time',
        'depOffice_date',
        'depOffice_time',
        'arrivalSite_date',
        'arrivalSite_time',
        'indented_date',
        'indented_time',
        'wellReadiness_date',
        'wellReadiness_time',
        'wellTaken_date',
        'wellTaken_time',
        'rigUP_date',
        'rigUP_time',
        'wellHandOver_date',
        'wellHandOver_time',
        'depSite_date',
        'depSite_time',
        'arrivalOffice_date',
        'arrivalOffice_time',
        'preparationTime',
        'postProceTime',
        'depthDriller',
        'depthLogger',
        'casingSize',
        'casingShoeDriller',
        'casingShoeLogger',
        'floatCollar',
        'bitSize',
        'tubingSize',
        't_shoe_Packer',
        's_nippletopexp',
        'THP',
        'maxDevAt',
        'distTo_FroKms',
        'rm',
        'rmtemp',
        'rmf',
        'rmftemp',
        'rmc',
        'rmctemp',
        'bht',
        'bhtdepth',
        'spgr',
        'viscosity',
        'mudType',
        'waterloss',
        'ph',
        'oilpercnt',
        'kcl_barytes',
        'salinity',
        'lastcirc_from',
        'lastcirc_to',
        'cableSize',
        'insulation',
        'shoeDate',
        'weakPoint',
        'cableHeadSize',
        'cableLength',
        'initialLength',
        'surfaceEquipment',
        'automobile',
        'wellCondition',
        'timeLoss',
        'personnel',
        'runNo',
        'logRecorded',
        'bottomDepth',
        'topDepth',
        'toolNo',
        'logQuality',
        'bottomShotDepth',
        'topShotDepth',
        'charge',
        'chargeNo',
        'primaChord',
        'primaChordQty',
        'fuse',
        'fuseNo',
        'fMf',
        'attempted',
        'recovered',
        'missFire',
        'barrelLost',
        'emptyBarrel',
        'chargeUsed',
        'permitType',
        'permitNo',
        'permitWork',
        'elecLockout',
        'elecLockoutNo',
        'safetyMeeting',
        'jobCloseMeeting',
        'nearMiss',
        'nearMissDesc',
        'jobStatus',
        'remarks',
        'objective',
        'observations',
        'contingents',
        'created_by',
        'created_at',
        'last_edited_by',
        'last_edited_at',
        'final_submitted',
        'final_submitted_by',
        'final_submitted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'jobDate' =>  'datetime:Y-m-d',
        'workOrderDate' =>  'datetime:Y-m-d',
        'assembled_date' => 'datetime:Y-m-d',
        'assembled_time' => 'datetime:h:m',
        'depOffice_date' => 'datetime:Y-m-d',
        'depOffice_time' => 'datetime:h:m',
        'arrivalSite_date' => 'datetime:Y-m-d',
        'arrivalSite_time' => 'datetime:h:m',
        'indented_date' => 'datetime:Y-m-d',
        'indented_time' => 'datetime:h:m',
        'wellReadiness_date' => 'datetime:Y-m-d',
        'wellReadiness_time' => 'datetime:h:m',
        'wellTaken_date' => 'datetime:Y-m-d',
        'wellTaken_time' => 'datetime:h:m',
        'rigUP_date' => 'datetime:Y-m-d',
        'rigUP_time' => 'datetime:h:m',
        'wellHandOver_date' => 'datetime:Y-m-d',
        'wellHandOver_time' => 'datetime:h:m',
        'depSite_date' => 'datetime:Y-m-d',
        'depSite_time' => 'datetime:h:m',
        'arrivalOffice_date' => 'datetime:Y-m-d',
        'arrivalOffice_time' => 'datetime:h:m',
        'shoeDate' =>  'datetime:Y-m-d',
        'lastcirc_from' => 'datetime:Y-m-d h:m',
        'lastcirc_to' => 'datetime:Y-m-d h:m',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'jcruser');
    }

    public function logs()
    {
        return $this->hasMany(logsRecorded::class);
    }
    public function explosives()
    {
        return $this->hasMany(explosiveUsed::class);
    }
}
