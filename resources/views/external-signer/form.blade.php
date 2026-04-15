@extends('layouts.public')

@section('title', 'External Signer Form')

@section('content')
    <div class="container py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Checklist B - External Signer</h4>
                    </div>
                    <div class="card-body">
                        @include('checklists.partials._preview_content')

                        @if($checklist->creatorSignature)
                            <div class="signature mb-3 p-3 border rounded">
                                <h5>Creator Signature</h5>
                                <div class="d-flex align-items-center">
                                    <div class="signature-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        {{ substr($checklist->creatorSignature->user->name, 0, 1) }}
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ $checklist->creatorSignature->user->name }}</strong><br>
                                        {{ $checklist->creatorSignature->signed_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                @if($checklist->creatorSignature->comments)
                                    <div class="mt-2 text-muted"><strong>Comments:</strong>
                                        {{ $checklist->creatorSignature->comments }}</div>
                                @endif
                            </div>
                        @endif

                        <hr>

                        <form method="POST" action="{{ route('external-signer.store', $checklist->id) }}">
                            @csrf
                            <h5 class="mb-3">Your Information</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation *</label>
                                <input type="text" class="form-control" id="designation" name="designation" required>
                            </div>

                            <div class="mb-3">
                                <label for="cpf_no" class="form-label">CPF Number *</label>
                                <input type="text" class="form-control" id="cpf_no" name="cpf_no" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-signature"></i> Submit Signature
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection