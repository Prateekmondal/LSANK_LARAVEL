@extends('layouts.app')

@section('title', 'Forward Checklist')

@section('content')
<div class="container">
    <div class="card w-100">
        <div class="card-header">
            <h2>Forward Checklist for Signature</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.send-forward', $checklist->id) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Checklist Details</label>
                    <div class="border p-3 rounded">
                        <div><strong>Type:</strong> {{ $checklist->type_name }}</div>
                        <div><strong>Well Name:</strong> {{ $checklist->well_no }}</div>
                        <div><strong>Date:</strong> {{ $checklist->date->format('d/m/Y') }}</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User to Forward To *</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">-- Select User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ Str::title($user->name) }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Message *</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                    <small class="text-muted">Explain why you're forwarding this checklist</small>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('checklists.show', $checklist->id) }}" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Forward Checklist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection