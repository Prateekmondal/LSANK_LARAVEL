@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                <!-- @include('jcr._preview_content') -->

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
            <!-- Signature Forms for authorized users -->
            @if($jcr->isPendingPartyChief() && Auth::user()->hasRole('party_chief'))
                <hr>
                <div class="signature-form">
                    <h5>Sign as Party Chief</h5>
                    <div class="d-flex">
                        <a href="{{ route('jcr.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning mx-2">
                            <i class="fas fa-edit"></i> Edit JCR
                        </a>
                        <form action="{{ route('jcr.party-chief.sign', $jcr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success mx-2">
                                <i class="fas fa-signature"></i> Sign as Party Chief
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($jcr->isPendingOperationIncharge() && Auth::user()->hasRole('operation_incharge'))
                <hr>
                <div class="signature-form">
                    <h5>Approve/Reject as Operation Incharge</h5>
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

                </div>
            @endif

            <div class="mt-4">
                @if($jcr->isDraft())
                    <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit JCR
                    </a>
                    <a href="{{ route('jcr.preview', $jcr->id) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Preview & Submit
                    </a>
                    <a href="{{ route('jcr.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                @endif

            </div>
        </div>
    </div>
    </div>
@endsection