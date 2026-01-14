<div class="row">
    <div class="col-md-6">
        <p><strong>Logging Unit No:</strong> {{ $timeRegister->logging_unit_no }}</p>
        <p><strong>Indent No:</strong> {{ $timeRegister->indent_no }}</p>
        <p><strong>Well No:</strong> {{ $timeRegister->well_no }}</p>
        <p><strong>Rig No:</strong> {{ $timeRegister->rig_no }}</p>
        
        <!-- Well Indented Display -->
        <p><strong>Well Indented:</strong> 
            @if($timeRegister->well_indented_time && $timeRegister->well_indented_date)
            {{ date('H:i', strtotime($timeRegister->well_indented_time)) }} on {{ $timeRegister->getFormattedWellIndentedDate() }}
            @else
            N/A
            @endif
        </p>

        <!-- Well Taken Up Display -->
        <p><strong>Well Taken Up:</strong> 
            @if($timeRegister->well_taken_up_time && $timeRegister->well_taken_up_date)
            {{ date('H:i', strtotime($timeRegister->well_taken_up_time)) }} on {{ $timeRegister->getFormattedWellTakenUpDate() }}
            @else
            N/A
            @endif
        </p>

        <!-- Well Handed Over Display -->
        <p><strong>Well Handed Over:</strong> 
            @if($timeRegister->well_handed_over_time && $timeRegister->well_handed_over_date)
            {{ date('H:i', strtotime($timeRegister->well_handed_over_time)) }} on {{ $timeRegister->getFormattedWellHandedOverDate() }}
            @else
            N/A
            @endif
        </p>
    </div>
    <div class="col-md-6">
        <p><strong>Rig Representative Email:</strong> {{ $timeRegister->rig_representative_email ?? 'N/A' }}</p>
        <p><strong>Status:</strong> 
            <span class="badge 
                bg-{{ (($timeRegister->status === 'draft' ? 'warning' :
                $timeRegister->status === 'preview') ? 'info' :
                $timeRegister->status === 'pending_signature') ? 'primary' :
                'success' }}">
                {{ ucfirst(str_replace('_', ' ', $timeRegister->status)) }}
            </span>
        </p>
        <p><strong>Created By:</strong> {{ $timeRegister->creator->name }}</p>
        <p><strong>Created At:</strong> {{ $timeRegister->created_at->format('Y-m-d H:i') }}</p>
        @if($timeRegister->is_final_submitted)
        <p><strong>Final Submitted At:</strong> {{ $timeRegister->final_submitted_at->format('Y-m-d H:i') }}</p>
        @endif
    </div>
</div>

<hr>

<h5>Job Carried out:</h5>
<div class="border p-3 bg-light">
    {{ $timeRegister->job_carried_out }}
</div>

<h5 class="mt-3">Observations by Logging Party Chief:</h5>
<div class="border p-3 bg-light">
    {{ $timeRegister->observations_by_logging_chief }}
</div>
<h5 class="mt-3">Observations by Rig In-Charge:</h5>
<div class="border p-3 bg-light">
    <p><strong>Observations:</strong> {{ $timeRegister->rig_representative_observations }}</p>
</div>


<div class="row">
    <div class="col-md-6">
        <!-- Logging Chief Signature Section -->
        <div class="card mt-4 w-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-shield"></i> Logging Chief</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $timeRegister->logging_chief_name }}</p>
                <p><strong>Designation:</strong> {{ $timeRegister->logging_chief_designation }}</p>
                <p><strong>Signed At:</strong> {{ $timeRegister->logging_chief_signed_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- Rig Representative Signature Section -->
        @if($timeRegister->rig_representative_signature)
        <div class="card mt-4 w-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-signature"></i> Rig Representative Signature</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $timeRegister->rig_representative_name }}</p>
                <p><strong>Designation:</strong> {{ $timeRegister->rig_representative_designation }}</p>
                <p><strong>Signed At:</strong> {{ $timeRegister->rig_representative_signed_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
        @elseif($timeRegister->is_final_submitted)
        <div class="alert alert-info mt-4">
            <h5><i class="fas fa-clock"></i> Pending Rig Representative Signature</h5>
            <p class="mb-0">Signature request sent to: <strong>{{ $timeRegister->rig_representative_email }}</strong></p>
        </div>
        @endif
    </div>
</div>
