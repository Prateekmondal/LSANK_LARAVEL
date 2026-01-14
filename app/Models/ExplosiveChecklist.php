<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

use App\Traits\Auditable;

class ExplosiveChecklist extends Model
{
    use HasFactory, Auditable;

    protected $casts = [
        'checklist_data' => 'array',
        'date' => 'date',
    ];

    protected $fillable = [
        'jcr_id',
        'type',
        'well_no',
        'rig',
        'logging_unit_no',
        'job_type',
        'perf_interval',
        'date',
        'checklist_data',
        'status',
        'creator_id',
        'sign_status',
        'external_sign_status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function signatures()
    {
        return $this->hasMany(ChecklistSignature::class);
    }

    public function creatorSignature()
    {
        return $this->hasOne(ChecklistSignature::class)->where('signature_type', 'creator');
    }

    public function approverSignature()
    {
        return $this->hasOne(ChecklistSignature::class)->where('signature_type', 'approver');
    }

    public function forwards()
    {
        return $this->hasMany(ChecklistForward::class);
    }

    public function jcr()
    {
        return $this->belongsTo(Jcr::class);
    }

    public function getTypeNameAttribute()
    {
        return [
            'a' => 'Pre-Departure',
            'b' => 'On-Site',
            'c' => 'Upon-Arrival'
        ][$this->type] ?? 'Unknown';
    }

    public function needsCreatorSignature()
    {
        return !$this->signatures()->where('signature_type', 'creator')->exists();
    }

    public function needsApproverSignature()
    {
        return !$this->signatures()->where('signature_type', 'approver')->exists();
    }

    // Add this to your existing model
    public function externalSignature()
    {
        return $this->hasOne(ExternalSignature::class);
    }

    public function requiresExternalSignature()
    {
        return $this->type === 'b'; // Only Checklist B requires external sign
    }

    public function isFullySigned()
    {
        if ($this->type === 'b') {
            return $this->signatures()->where('signature_type', 'creator')->exists() &&
                $this->externalSignature()->exists();
        }
        return $this->signatures()->count() >= 2;
    }
}