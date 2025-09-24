@extends('layouts.public')

@section('title', 'External Signer Form')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Checklist B - External Signer</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Checklist Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Well Name:</strong> {{ $checklist->well_name }}</p>
                                <p><strong>Job Type:</strong> {{ $checklist->job_type }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date:</strong> {{ $checklist->date->format('Y-m-d') }}</p>
                                <p><strong>Created By:</strong> {{ $checklist->creator->name }}</p>
                            </div>
                        </div>
                    </div>

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