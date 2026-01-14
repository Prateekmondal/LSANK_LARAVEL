<?php
// app/Models/TimeRegister.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TimeRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'logging_unit_no',
        'indent_no',
        'well_no',
        'rig_no',
        
        // Separate date and time fields
        'well_indented_date',
        'well_indented_time',
        'well_taken_up_date',
        'well_taken_up_time',
        'well_handed_over_date',
        'well_handed_over_time',
        
        'job_carried_out',
        'observations_by_logging_chief',
        
        // Logging Chief details
        'logging_chief_id',
        'logging_chief_name',
        'logging_chief_designation',
        'logging_chief_signature',
        'logging_chief_signed_at',
        
        // Rig Representative Information
        'rig_representative_email',
        'rig_representative_observations',
        'rig_representative_signature',
        'rig_representative_name',
        'rig_representative_designation',
        'rig_representative_signed_at',
        
        'status',
        'signature_token',
        'is_final_submitted',
        'final_submitted_at',
        'created_by',
    ];

    protected $casts = [
        'well_indented_date' => 'datetime:Y-m-d',
        'well_indented_time' => 'datetime:h:m',
        'well_taken_up_date' => 'datetime:Y-m-d',
        'well_taken_up_time' => 'datetime:h:m',
        'well_handed_over_date' => 'datetime:Y-m-d',
        'well_handed_over_time' => 'datetime:h:m',
        'logging_chief_signed_at' => 'datetime',
        'rig_representative_signed_at' => 'datetime',
        'final_submitted_at' => 'datetime',
        'is_final_submitted' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->signature_token = Str::random(32);
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loggingChief()
    {
        return $this->belongsTo(User::class, 'logging_chief_id');
    }

    public function jcr()
    {
        return $this->hasOne(Jcr::class, 'time_register_id');
    }

    // Scopes
    public function scopeAvailableForLinking($query)
    {
        return $query->whereDoesntHave('jcr')
                    ->where(function($q) {
                        $q->where('is_final_submitted', true)
                          ->orWhere('status', 'completed');
                    });
    }

    /**
     * Exclude time registers that already have all three checklist types (a, b, c) linked to JCRs
     * i.e. if for the same well_no and logging_unit_no there exist linked checklists of types a, b and c,
     * that time register should not be offered for new JCR linking.
     */
    public function scopeWithoutFullyLinkedChecklists($query)
    {
        return $query->whereNotExists(function ($q) {
            $q->select(DB::raw(1))
              ->from('explosive_checklists')
              ->whereColumn('explosive_checklists.logging_unit_no', 'time_registers.logging_unit_no')
              ->whereColumn('explosive_checklists.well_no', 'time_registers.well_no')
              ->whereNotNull('explosive_checklists.jcr_id')
              ->groupBy('explosive_checklists.logging_unit_no', 'explosive_checklists.well_no')
              ->havingRaw('COUNT(DISTINCT explosive_checklists.type) = 3');
        });
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePreview($query)
    {
        return $query->where('status', 'preview');
    }

    public function scopePendingSignature($query)
    {
        return $query->where('status', 'pending_signature');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFinalSubmitted($query)
    {
        return $query->where('is_final_submitted', true);
    }

    // Check if time register is available for linking
    public function isAvailableForLinking()
    {
        return $this->is_final_submitted || $this->status === 'completed';
    }

    // Methods for workflow
    public function canBeEditedBy($user)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $this->created_by === $user->id && !$this->is_final_submitted;
    }

    public function isEditable()
    {
        return !$this->is_final_submitted || auth()->user()->hasRole('super-admin');
    }

    public function getSignatureUrl()
    {
        return route('time-registers.rig-signature', $this->signature_token);
    }

    // Get display summary for selection modal
    public function getSelectionSummary()
    {
        return "Unit: {$this->logging_unit_no} | Well: {$this->well_no} | Rig: {$this->rig_no} | Job: " . substr($this->job_carried_out, 0, 50) . "...";
    }

    // Format date for display
    public function getFormattedWellIndentedDate()
    {
        return $this->well_indented_date ? $this->well_indented_date->format('M j, Y') : 'N/A';
    }

    public function getFormattedWellTakenUpDate()
    {
        return $this->well_taken_up_date ? $this->well_taken_up_date->format('M j, Y') : 'N/A';
    }

    public function getFormattedWellHandedOverDate()
    {
        return $this->well_handed_over_date ? $this->well_handed_over_date->format('M j, Y') : 'N/A';
    }

    // Check if logging chief has signed
    public function hasLoggingChiefSigned()
    {
        return !is_null($this->logging_chief_signed_at);
    }

    // Check if rig representative has signed
    public function hasRigRepresentativeSigned()
    {
        return !is_null($this->rig_representative_signed_at);
    }
}