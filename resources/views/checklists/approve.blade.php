@extends('layouts.app')

@section('title', 'Approve Checklist')

@section('content')
<div class="container">
    <div class="card mb-4 w-75">
        <div class="card-header">
            <h2>Checklist Approval</h2>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Type:</strong> {{ $checklist->type_name }}</div>
                <div class="col-md-4"><strong>Well Name:</strong> {{ $checklist->well_name }}</div>
                <div class="col-md-4"><strong>Date:</strong> {{ $checklist->date->format('d/m/Y') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Job Type:</strong> {{ $checklist->job_type }}</div>
                <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($checklist->status) }}</div>
                <div class="col-md-4"><strong>Created By:</strong> {{ $checklist->creator->name }}</div>
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
                                    {{ $item['status'] ? 'Completed' : 'Not Completed' }}
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
    
    <div class="card">
        <div class="card-header">
            <h3>Approve Checklist</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.approve', $checklist->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Approving as: <strong>{{ auth()->user()->name }}</strong></label>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Approval Comments</label>
                    <textarea class="form-control" name="comments" rows="3" required></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('checklists.show', $checklist->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Checklist
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Approve Checklist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection