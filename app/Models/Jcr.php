<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

use App\Traits\Auditable;

class Jcr extends Model
{
    use HasPermissions, Auditable;
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
        'logging_unit_type',
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
        'created_at',
        'final_submit',
        'creator_id',
        'creator_signature',
        'creator_signed_at',
        'party_chief_edited',
        'party_chief_id',
        'party_chief_signature',
        'party_chief_signed_at',
        'operation_incharge_edited',
        'operation_incharge_id',
        'operation_incharge_signature',
        'operation_incharge_signed_at',
        'status',
        'time_register_id',
        'time_register_linked',
        'sap_document_number',
        'sap_pushed_at',
        'sap_status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'jobDate' => 'datetime:Y-m-d',
        'workOrderDate' => 'datetime:Y-m-d',
        'assembled_date' => 'datetime:Y-m-d',
        // Time-only fields: keep as strings to avoid timezone/Carbon conversions
        'assembled_time' => 'string',
        'depOffice_date' => 'datetime:Y-m-d',
        'depOffice_time' => 'string',
        'arrivalSite_date' => 'datetime:Y-m-d',
        'arrivalSite_time' => 'string',
        'indented_date' => 'datetime:Y-m-d',
        'indented_time' => 'string',
        'wellReadiness_date' => 'datetime:Y-m-d',
        'wellReadiness_time' => 'string',
        'wellTaken_date' => 'datetime:Y-m-d',
        'wellTaken_time' => 'string',
        'rigUP_date' => 'datetime:Y-m-d',
        'rigUP_time' => 'string',
        'wellHandOver_date' => 'datetime:Y-m-d',
        'wellHandOver_time' => 'string',
        'depSite_date' => 'datetime:Y-m-d',
        'depSite_time' => 'string',
        'arrivalOffice_date' => 'datetime:Y-m-d',
        'arrivalOffice_time' => 'string',
        'shoeDate' => 'datetime:Y-m-d',
        // Fix malformed format tokens: use H:i for 24-hour minutes
        'lastcirc_from' => 'datetime:Y-m-d H:i',
        'lastcirc_to' => 'datetime:Y-m-d H:i',
        'time_register_linked' => 'boolean',
        'sap_pushed_at' => 'datetime',
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

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_CREATOR = 'pending_creator';
    const STATUS_PENDING_PARTY_CHIEF = 'pending_party_chief';
    const STATUS_PENDING_OPERATION_INCHARGE = 'pending_operation_incharge';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function getStatusAttribute($value)
    {
        return $value ?? self::STATUS_DRAFT;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function partyChief()
    {
        return $this->belongsTo(User::class, 'party_chief_id');
    }

    public function operationIncharge()
    {
        return $this->belongsTo(User::class, 'operation_incharge_id');
    }

    // Helper methods for status checking
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingCreator()
    {
        return $this->status === self::STATUS_PENDING_CREATOR;
    }

    public function isCreatorSigned()

    {
        return $this->creator_signature !== null && $this->creator_signed_at !== null;
    }

    public function isPendingPartyChief()
    {
        return $this->status === self::STATUS_PENDING_PARTY_CHIEF;
    }

    public function isPartyChiefEdited()
    {
        return $this->party_chief_edited === 1;
    }

    public function isPendingOperationIncharge()
    {
        return $this->status === self::STATUS_PENDING_OPERATION_INCHARGE;
    }

    public function isOperationInchargeEdited()
    {
        return $this->operation_incharge_edited === 1;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_APPROVED:
                return 'success';
            case self::STATUS_REJECTED:
                return 'danger';
            case self::STATUS_DRAFT:
                return 'secondary';
            case self::STATUS_PENDING_CREATOR:
                return 'warning';
            case self::STATUS_PENDING_PARTY_CHIEF:
                return 'info';
            case self::STATUS_PENDING_OPERATION_INCHARGE:
                return 'primary';
            default:
                return 'secondary';
        }
    }

    protected static function booted()
    {
        static::deleting(function ($jcr) {
            $jcr->checklists()->each(function ($checklist) {
                $checklist->delete();
            });
        });
    }

    public function checklists()
    {
        return $this->hasMany(ExplosiveChecklist::class);
    }

    // Backwards-compatible alias: some code expects `explosiveChecklists()`
    public function explosiveChecklists()
    {
        return $this->checklists();
    }

    // Relationship with TimeRegister
    public function timeRegister()
    {
        return $this->belongsTo(TimeRegister::class, 'time_register_id');
    }

    // Check if JCR requires time register linking
    public function requiresTimeRegisterLinking()
    {
        return !$this->time_register_linked || !$this->timeRegister;
    }

    // Get available time registers for linking
    public static function getAvailableTimeRegisters()
    {
        return TimeRegister::availableForLinking()->get();
    }

    // Scope for JCRs without time register
    public function scopeWithoutTimeRegister($query)
    {
        return $query->where('time_register_linked', false)
                    ->orWhereNull('time_register_id');
    }

    // Fix: This method was missing
    public function requiresTimeRegister()
    {
        return !$this->time_register_linked;
    }

    // Check if JCR is approved and fully signed (can be pushed to SAP)
    public function canPushToSap()
    {
        return $this->isApproved() && 
               $this->operation_incharge_signature !== null &&
               $this->sap_document_number === null;
    }

    // Check if already pushed to SAP
    public function isPushedToSap()
    {
        return !is_null($this->sap_document_number);
    }

    // Get SAP push timestamp formatted
    public function getSapPushedAtFormatted()
    {
        if ($this->sap_pushed_at) {
            return $this->sap_pushed_at->format('d-m-Y H:i:s');
        }
        return null;
    }
}
