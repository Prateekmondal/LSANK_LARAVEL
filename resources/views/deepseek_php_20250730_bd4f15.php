@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>{{ $checklist->type_name }} Checklist</h2>
            <div class="btn-group">
                @can('update', $checklist)
                    <a href="{{ route('checklists.edit', $checklist->id) }}" class="btn btn-warning">Edit</a>
                @endcan
                @can('forceEdit', $checklist)
                    <a href="{{ route('checklists.force-edit', $checklist->id) }}" class="btn btn-outline-danger">Admin Edit</a>
                @endcan
                @can('delete', $checklist)
                    <form action="{{ route('checklists.destroy', $checklist->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
        
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Well Name:</strong> {{ $checklist->well_name }}</div>
                <div class="col-md-4"><strong>Date:</strong> {{ $checklist->date->format('d/m/Y') }}</div>
                <div class="col-md-4"><strong>Job Type:</strong> {{ $checklist->job_type }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4"><strong>Status:</strong> {{ ucfirst($checklist->status) }}</div>
                <div class="col-md-4"><strong>Created By:</strong> {{ $checklist->creator->name }}</div>
                <div class="col-md-4"><strong>Signed By:</strong> {{ $checklist->signer?->name ?? 'Not signed' }}</div>
            </div>
            
            <table class="table table-bordered">
                <thead>
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
                        <td>{{ $item['status'] ? 'Completed' : 'Not Completed' }}</td>
                        <td>{{ $item['comments'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($checklist->signatures->count())
                <div class="mt-4">
                    <h4>Signatures</h4>
                    @foreach($checklist->signatures as $signature)
                        <div class="signature mb-3">
                            <img src="{{ $signature->signature }}" alt="Signature" style="max-height: 50px;">
                            <div>{{ $signature->user->name }} - {{ $signature->signed_at->format('d/m/Y H:i') }}</div>
                            @if($signature->comments)
                                <div class="text-muted">Comments: {{ $signature->comments }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    @if($checklist->status === 'draft')
        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body">
                <a href="{{ route('checklists.preview', $checklist->id) }}" class="btn btn-primary">Preview & Sign</a>
                <a href="{{ route('checklists.forward', $checklist->id) }}" class="btn btn-secondary">Forward for Signature</a>
            </div>
        </div>
    @endif
</div>
@endsection