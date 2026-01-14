<!-- resources/views/logging-units/rig-signature.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rig Representative Signature</h1>
    
    <div class="card">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="card-body">
            <h4>Logging job Details</h4>
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
            
            <p><strong>Job Carried out:</strong> {{ $timeRegister->job_carried_out }}</p>
            
            <!-- Logging Chief Signature Section -->
            <div class="card mb-4 w-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-signature"></i> Logging Chief Signature</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Name:</strong> {{ $timeRegister->logging_chief_name }}</p>
                            <p><strong>Designation:</strong> {{ $timeRegister->logging_chief_designation }}</p>
                            <p><strong>Signed At:</strong> {{ $timeRegister->logging_chief_signed_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <h4>Rig Representative Signature Form</h4>
            <form action="{{ route('time-registers.store-rig-signature', $timeRegister->signature_token) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Observations:<small>*</small></label>
                    <textarea name="rig_representative_observations" class="form-control" rows="4" required placeholder="1. Whether the Jobs fully carried out.&#10;2. Any other suggestions ______"></textarea>
                </div>

                <div class="form-group">
                    <label>Name:<small>*</small></label>
                    <input type="text" name="rig_representative_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Designation:<small>*</small></label>
                    <input type="text" name="rig_representative_designation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>


@endsection