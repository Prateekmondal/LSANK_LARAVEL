@extends('layouts.app')

@section('title', 'Preview Checklist')

@section('content')
    <div class="container">
        <div class="card mb-4" style="width: 95%;">
            <div class="card-header">
                <h2>{{ $checklist->type_name }} Checklist Preview</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Well Name:</strong> {{ $checklist->well_no }}</div>
                    <div class="col-md-3"><strong>Rig:</strong> {{ $checklist->rig }}</div>
                    <div class="col-md-3"><strong>Logging Unit:</strong> {{ $checklist->logging_unit_no }}</div>
                    <div class="col-md-3"><strong>Job Type:</strong> {{ $checklist->job_type }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Perforation Interval:</strong> {{ $checklist->perf_interval }}</div>
                    <div class="col-md-3"><strong>Date:</strong> {{ $checklist->date->format('d/m/Y') }}</div>
                    <div class="col-md-3"><strong>Status:</strong> {{ ucfirst($checklist->status) }}</div>
                    <div class="col-md-3"><strong>Created By:</strong> {{ ucfirst($checklist->creator->name) }}</div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($checklist->checklist_data as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item['status'] ? 'success' : 'danger' }}">
                                            {{ $item['status'] ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $item['comments'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($checklist->type === 'b')
            <div class="card w-75">
                <div class="card-header">
                    <h3>Confirm Checklist</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('checklists.confirm', $checklist->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Signing as: <strong>Creator</strong> ({{ auth()->user()->name }})</label>
                        </div>
                        <div class="mb-3">
                                <label for="external_email" class="form-label">Rig Incharge Email *</label>
                                <input type="email" class="form-control" id="external_email" name="external_email" value="{{ old('external_email', $checklist->external_email) }}" required>
                                <small class="text-muted">Enter the email of the Rig Incharge who needs to sign this
                                    checklist</small>
                            </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-warning">
                                <i class="bi bi-arrow-left"></i> Back to Edit
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirm Checklist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card w-75 mt-4">
                <div class="card-header">
                    <h4>Rig Incharge Signature</h4>
                </div>
                <div class="card-body">
                    @if($checklist->externalSignature)
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-1">Signed by Rig Incharge</h5>
                                    <p class="mb-1"><strong>Name:</strong> {{ $checklist->externalSignature->name }}</p>
                                    <p class="mb-1"><strong>Designation:</strong> {{ $checklist->externalSignature->designation }}
                                    </p>
                                    <p class="mb-1"><strong>CPF No:</strong> {{ $checklist->externalSignature->cpf_no }}</p>
                                    <p class="mb-0"><strong>Signed At:</strong>
                                        {{ $checklist->externalSignature->signed_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($checklist->external_sign_status === 'sent')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Request sent to external signer, waiting for response
                        </div>
                    @else
                        <!-- <form method="POST" action="{{ route('checklists.send-external', $checklist->id) }}">
                            @csrf
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Send for Signature
                            </button>
                        </form> -->
                    @endif
                </div>
            </div>
        @else
            <div class="card w-75">
                <div class="card-header">
                    <h3>Confirm Checklist</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('checklists.confirm', $checklist->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Signing as: <strong>Creator</strong> ({{ auth()->user()->name }})</label>
                        </div>

                        <div class="mb-3">
                            <label for="approver_id" class="form-label">Select Approver *</label>
                            <select class="form-select" id="approver_id" name="approver_id" required>
                                <option value="">-- Select Approver --</option>
                                @foreach($users as $user)
                                    @if($user->id !== auth()->id())
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments (Optional)</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-warning">
                                <i class="bi bi-arrow-left"></i> Back to Edit
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirm Checklist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection