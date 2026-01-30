@extends('layouts.app')

@section('title', $checklist->type_name . ' Checklist')

@section('content')
<div class="container">
    <div class="card mb-4 w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>{{ $checklist->type_name }} Checklist</h2>
            <div class="btn-group">
                @can('update', $checklist)
                    <a href="{{ route('checklists.edit', [$checklist->id, $checklist->type]) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endcan
                @can('delete', $checklist)
                    <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
        
        <div class="card-body">
            @include('checklists.partials.show-header-type-' . $checklist->type)
            
            <div class="row mb-3">
                <div class="col-md-4"><strong>Status:</strong> 
                    <span class="badge bg-{{ $checklist->status === 'signed' ? 'success' : ($checklist->status === 'completed' ? 'primary' : 'warning') }}">
                        {{ ucfirst($checklist->status) }}
                    </span>
                </div>
                <div class="col-md-4"><strong>Created By:</strong> {{ $checklist->creator->name }}</div>
                <div class="col-md-4"><strong>Confirmed:</strong> {{ $checklist->status === 'signed' ? 'Yes' : 'No' }}</div>
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
                                <span class="badge bg-{{ $item['status'] ? 'success' : ($item['status'] == null ? 'warning' : 'danger') }}">
                                    {{ $item['status'] ? 'Yes' : ($item['status'] == null ? 'N/A' : 'No') }}
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
    
    <!-- Signatures Section -->
    <div class="card mt-4 w-75">
        <div class="card-header">
            <h4>Signatures</h4>
        </div>
        <div class="card-body">
            @if($checklist->creatorSignature)
                <div class="signature mb-3 p-3 border rounded">
                    <h5>Creator Signature</h5>
                    <div class="d-flex align-items-center">
                        <div class="signature-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            {{ substr($checklist->creatorSignature->user->name, 0, 1) }}
                        </div>
                        <div class="ms-3">
                            <strong>{{ $checklist->creatorSignature->user->name }}</strong><br>
                            {{ $checklist->creatorSignature->signed_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @if($checklist->creatorSignature->comments)
                        <div class="mt-2 text-muted"><strong>Comments:</strong> {{ $checklist->creatorSignature->comments }}</div>
                    @endif
                </div>
            @endif

            <!-- Add this to the signatures section -->
            @if($checklist->forwards()->where('purpose', 'approval')->exists() and auth()->user()->id !== $checklist->forwards()->where('purpose', 'approval')->first()->to_user_id)
                <div class="mb-3">
                <h5>Approver Assignment</h5>
                @php $forward = $checklist->forwards()->where('purpose', 'approval')->first(); @endphp
                <div class="d-flex align-items-center">
                    <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        {{ substr($forward->toUser->name, 0, 1) }}
                    </div>
                    <div class="ms-3">
                        <strong>Assigned To:</strong> {{ $forward->toUser->name }}<br>
                                <small class="text-muted">Assigned on {{ $forward->created_at->format('M j, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                @elseif($checklist->forwards()->where('purpose', 'approval')->exists() and auth()->user()->id === $checklist->forwards()->where('purpose', 'approval')->first()->to_user_id and !$checklist->approverSignature)
                    <div class="mb-3">
                        <label for="comments" class="form-label">Approver comment (Optional)</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                    </div>
                    <a href="{{ route('checklists.approve', $checklist->id) }}" class="btn btn-success">
                        <i class="bi bi-arrow-right me-2"></i>Confirm
                    </a>
                @endif
                    
                @if($checklist->approverSignature)
                    <div class="signature mb-3 p-3 border rounded">
                        <h5>Approver Signature</h5>
                        <div class="d-flex align-items-center">
                        <div class="signature-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            {{ substr($checklist->approverSignature->user->name, 0, 1) }}
                        </div>
                        <div class="ms-3">
                            <strong>{{ $checklist->approverSignature->user->name }}</strong><br>
                            {{ $checklist->approverSignature->signed_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @if($checklist->approverSignature->comments)
                        <div class="mt-2 text-muted"><strong>Comments:</strong> {{ $checklist->approverSignature->comments }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    @if($checklist->status === 'draft')
        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('checklists.preview', $checklist->id) }}" class="btn btn-primary">
                        <i class="bi bi-eye"></i> Preview & Confirm
                    </a>
                </div>
            </div>
        </div>
    @elseif($checklist->type === 'b')
    <div class="card mt-4 w-75">
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
                            <p class="mb-1"><strong>Designation:</strong> {{ $checklist->externalSignature->designation }}</p>
                            <p class="mb-1"><strong>CPF No:</strong> {{ $checklist->externalSignature->cpf_no }}</p>
                            <p class="mb-0"><strong>Signed At:</strong> {{ $checklist->externalSignature->signed_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            @elseif($checklist->external_sign_status === 'sent')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    Request sent to external signer, waiting for response
                </div>
            @else
                <form method="POST" action="{{ route('checklists.send-external', $checklist->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="external_email" class="form-label">Rig Incharge Email *</label>
                        <input type="email" class="form-control" id="external_email" name="external_email" required>
                        <small class="text-muted">Enter the email of the Rig Incharge who needs to sign this checklist</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send for Signature
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif
    
</div>

@push('styles')
<style>
    .signature-avatar {
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>
@endpush
@endsection