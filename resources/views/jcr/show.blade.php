@extends('layouts.app')

@section('title', 'JCR #' . $jcr->id)

@section('content')
<div class="container-fluid">
    <div class="card mb-4 w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Job Completion Report #{{ $jcr->id }}</h2>
            <div>
                <a href="{{ route('jcr.print', $jcr->id) }}" class="btn btn-primary">
                    <i class="fas fa-print"></i> Printable View
                </a>
                @if($jcr->canPushToSap() && Auth::user()->hasRole('Technical_Support_Group'))
                    <form action="{{ route('jcr.push-to-sap', $jcr->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to push this JCR to SAP?')">
                            <i class="fas fa-arrow-up"></i> Push to SAP
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            @include('jcr._preview_content')
            
            <h4 class="mt-4 mb-3">Attached Checklists</h4>
            
            <div class="list-group">
                @foreach($groupedChecklists as $type => $checklist)
                    @if($checklist)
                    <a href="{{ route('checklists.show', $checklist->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $checklist->type_name }} Checklist</h5>
                                <small class="text-muted">
                                    Status: 
                                    <span class="badge bg-{{ $checklist->status === 'signed' ? 'success' : ($checklist->status === 'completed' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($checklist->status) }}
                                    </span>
                                </small>
                            </div>
                            <div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                    @else
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    @if($type === 'a') Pre-Departure
                                    @elseif($type === 'b') On-Site
                                    @elseif($type === 'c') Upon-Arrival
                                    @endif Checklist
                                </h5>
                                <small class="text-danger">Not attached</small>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            <!-- Signature Status -->
                <hr>
                <h4 class="text-primary">Signatures</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card w-100 {{ $jcr->creator_signature ? 'border-success' : 'border-secondary' }}">
                            <div class="card-body text-center">
                                <h5>Creator</h5>
                                @if($jcr->creator_signature)
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mt-2 mb-0">{{ $jcr->creator_signature }}</p>
                                        <small>{{ $jcr->creator_signed_at }}</small>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-clock fa-2x"></i>
                                        <p class="mt-2 mb-0">Pending</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card w-100 {{ $jcr->party_chief_signature ? 'border-success' : 'border-secondary' }}">
                            <div class="card-body text-center">
                                <h5>Party Chief</h5>
                                @if($jcr->party_chief_signature)
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mt-2 mb-0">{{ $jcr->party_chief_signature }}</p>
                                        <small>{{ $jcr->party_chief_signed_at }}</small>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-clock fa-2x"></i>
                                        <p class="mt-2 mb-0">Pending</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div
                            class="card w-100 {{ $jcr->operation_incharge_signature ? 'border-success' : 'border-secondary' }}">
                            <div class="card-body text-center">
                                <h5>Operation Incharge</h5>
                                @if($jcr->operation_incharge_signature)
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mt-2 mb-0">{{ $jcr->operation_incharge_signature }}</p>
                                        <small>{{ $jcr->operation_incharge_signed_at }}</small>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-clock fa-2x"></i>
                                        <p class="mt-2 mb-0">Pending</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SAP Document Information -->
            @if($jcr->isPushedToSap())
                <hr>
                <h4 class="text-primary">SAP Integration</h4>
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>SAP Document Number:</strong>
                            <span class="badge bg-success">{{ $jcr->sap_document_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Pushed at:</strong>
                            {{ $jcr->getSapPushedAtFormatted() }}
                        </div>
                    </div>
                </div>
            @endif
            <!-- Signature Forms for authorized users -->
            <div class="my-3">
                <hr>
                @if($jcr->isCreatorSigned() && $jcr->isPendingPartyChief() && Auth::user()->hasRole('party_chief') && auth()->user()->id == $jcr->party_chief_id)
                    <h5>Sign as Party Chief</h5>
                    <div class="d-flex">
                        <a href="{{ route('jcr.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @can('update', $jcr)
                            <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning mx-2">
                                <i class="fas fa-edit"></i> Edit JCR
                            </a>
                        @endcan
                        <form action="{{ route('jcr.party-chief.sign', $jcr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success mx-2">
                                <i class="fas fa-signature"></i> Sign as Party Chief
                            </button>
                        </form>
                    </div>
                @elseif($jcr->isPendingOperationIncharge() && Auth::user()->hasRole('operation_incharge'))
                    <h5>Approve/Reject as Operation Incharge</h5>
                    <br>
                    <form action="{{ route('jcr.operation-incharge.sign', $jcr->id) }}" method="POST">
                        @csrf
                        <div class="form-group my-2">
                            <select name="action" class="form-control" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>
                        <a href="{{ route('jcr.index') }}" class="btn btn-secondary w-25 m-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <button type="submit" class="btn btn-success m-2">
                            <i class="fas fa-check"></i> Submit Decision
                        </button>
                    </form>
                @else
                    {{-- Creator can assign a party chief and submit final JCR in one action --}}
                    @if(auth()->check())
                        <div class="mb-4">
                            <form action="{{ route('jcr.submit', $jcr->id) }}" method="POST" class="form-inline">
                                @csrf
                                @if(empty($jcr->party_chief_id))
                                    <div class="form-group mr-2">
                                        <label for="party_chief_id" class="mr-2">Forward to Party Chief :</label>
                                        <select name="party_chief_id" id="party_chief_id" class="form-control mb-1" required>
                                            <option value="" disabled selected>-- Select Party Chief --</option>
                                            @foreach($partyChiefs as $pc)
                                                <option value="{{ $pc->id }}">{{ ucwords(strtolower($pc->name)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg mx-2 mt-2">
                                        <i class="fas fa-check-circle"></i> Submit Final JCR
                                    </button>
                                @else
                                    <div class="alert alert-info">
                                        Assigned Party Chief: {{ optional($jcr->partyChief)->name ?? 'N/A' }}
                                    </div>
                                @endif
                                <a href="{{ route('jcr.index') }}" class="btn btn-secondary btn-lg mx-2 mt-2">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                @can('update', $jcr)
                                    <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-lg mx-2 mt-2">
                                        <i class="fas fa-edit"></i> Edit JCR
                                    </a>
                                @endcan
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection