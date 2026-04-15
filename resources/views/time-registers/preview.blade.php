@extends('layouts.app')

@section('content')

@if(session('from_jcr'))
<div class="alert alert-info">
    <strong>Note:</strong> After final submission, this Time Register will be available for linking to JCR.
</div>

<div class="card-footer">
    <form action="{{ route('time-registers.final-submit', $timeRegister) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to final submit? This action cannot be undone.')">
            Final Submit & Return to JCR Creation
        </button>
    </form>
    <a href="{{ route('time-registers.edit', $timeRegister) }}" class="btn btn-warning">Edit Details</a>
</div>
@endif
<div class="container">
    <h1>Preview Time Register</h1>
    <div class="alert alert-info">
        <strong>Please review all details carefully before final submission.</strong>
        <br>Once final submitted, you cannot edit this Time Register (unless you are a Super Admin).
    </div>
    
    @if(session('from_jcr'))
    <div class="alert alert-warning">
        <strong>Note:</strong> After final submission, this Time Register will be available for linking to JCR.
    </div>
    @endif

    <div class="card w-100">
        <div class="card-header">
            <h4>Time Register Details</h4>
        </div>
        <div class="card-body">
            <!-- Logging Chief Signature Section -->
            <div class="card mb-4">
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

            @include('time-registers._preview')
        </div>
        <div class="card-footer">
            <!-- Rig Representative Email Form -->
            <form action="{{ route('time-registers.final-submit', $timeRegister) }}" method="POST">
                @csrf
                
                @if(session('from_jcr'))
                <input type="hidden" name="from_jcr" value="1">
                @endif

                <div class="form-group">
                    <label for="rig_representative_email"><strong>Rig Representative Email *</strong></label>
                    <input type="email" name="rig_representative_email" class="form-control" 
                           value="{{ old('rig_representative_email', $timeRegister->rig_representative_email) }}" 
                           required placeholder="Enter rig representative email address">
                    <small class="form-text text-muted">
                        Signature request will be sent to this email for final approval.
                    </small>
                </div>

                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to final submit? This action cannot be undone and will send a signature request to the rig representative.')">
                    <i class="fas fa-paper-plane"></i> Final Submit & Send Signature Request
                </button>
            </form>

            <hr>

            <div class="mt-3">
                <a href="{{ route('time-registers.edit', $timeRegister) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Details
                </a>
                <a href="{{ route('time-registers.index') }}" class="btn btn-light">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection