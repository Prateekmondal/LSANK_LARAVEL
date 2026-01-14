<!-- resources/views/time-registers/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Time Register Details</h1>
    
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Time Register #{{ $timeRegister->id }}</h4>
            <div>
                @if($timeRegister->is_final_submitted)
                <span class="badge badge-success">Final Submitted</span>
                @else
                <span class="badge badge-warning">{{ ucfirst($timeRegister->status) }}</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            @include('time-registers._preview')
        </div>
        <div class="card-footer">
            <a href="{{ route('time-registers.index') }}" class="btn btn-secondary">Back to List</a>
            
            @if($timeRegister->canBeEditedBy(auth()->user()))
            <a href="{{ route('time-registers.edit', $timeRegister) }}" class="btn btn-warning">Edit</a>
            @endif

            @if($timeRegister->status === 'preview' && !$timeRegister->is_final_submitted)
            <a href="{{ route('time-registers.preview', $timeRegister) }}" class="btn btn-primary">Complete Final Submission</a>
            @endif
            
            @if($timeRegister->is_final_submitted && $timeRegister->rig_representative_email && !$timeRegister->rig_representative_signature)
            <form action="{{ route('time-registers.resend', $timeRegister) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">Resend Email</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection