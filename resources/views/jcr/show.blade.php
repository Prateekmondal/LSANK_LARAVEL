@extends('layouts.app')

@section('title', 'JCR #' . $jcr->id)

@section('content')
<div class="container">
    <div class="card mb-4 w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Job Completion Report #{{ $jcr->id }}</h2>
            <div>
                <a href="{{ route('jcr.print', $jcr->id) }}" class="btn btn-primary">
                    <i class="fas fa-print"></i> Printable View
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('jcr._preview_content')
            <!-- <div class="row mb-3">
                <div class="col-md-4"><strong>Well Name:</strong> {{ $jcr->well_name }}</div>
                <div class="col-md-4"><strong>Date:</strong> {{ $jcr->jobDate->format('Y-m-d') }}</div>
                <div class="col-md-4"><strong>Created By:</strong> {{ $jcr->creator->name }}</div>
            </div> -->
            
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
        </div>
    </div>
</div>
@endsection