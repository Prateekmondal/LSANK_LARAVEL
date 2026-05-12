<!-- resources/views/time-registers/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Time Registers</h1>
    
    @if(auth()->user()->can('create', App\Models\TimeRegister::class))
    <a href="{{ route('time-registers.create') }}" class="btn btn-primary mb-3">Create New Time Register</a>
    @endif

    <div class="card w-100">
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Logging Unit No</th>
                        <th>Well No</th>
                        <th class="d-none d-lg-table-cell">Rig No</th>
                        <th class="d-none d-lg-table-cell">Status</th>
                        <th class="d-none d-lg-table-cell">Final Submitted</th>
                        <th class="d-none d-lg-table-cell">Created By</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeRegisters as $register)
                    <tr>
                        <td class="align-middle">{{ $register->logging_unit_no }}</td>
                        <td class="align-middle">{{ $register->well_no }}</td>
                        <td class="d-none d-lg-table-cell align-middle">{{ $register->rig_no }}</td>
                        <td class="d-none d-lg-table-cell align-middle">
                            <span class="badge @if($register->status === 'draft') badge-warning bg-warning @elseif($register->status === 'pending_signature') badge-info bg-info @else badge-success bg-success @endif">
                                {{ ucfirst(str_replace('_', ' ', $register->status)) }}
                            </span>
                        </td>
                        <td class="d-none d-lg-table-cell align-middle">
                            @if($register->is_final_submitted)
                                <span class="badge badge-success bg-success">Yes</span>
                                <br><small>{{ $register->final_submitted_at->format('M j, Y H:i') }}</small>
                            @else
                                <span class="badge badge-secondary bg-secondary">No</span>
                            @endif
                        </td>
                        <td class="d-none d-lg-table-cell align-middle">{{ $register->creator->name }}</td>
                        <td class="text-center align-middle">
                            <div class="d-flex gap-2 justify-content-center align-items-center">
                                <a href="{{ route('time-registers.show', $register) }}" class="btn btn-info btn-sm">View</a>
                                
                                @if($register->canBeEditedBy(auth()->user()))
                                <a href="{{ route('time-registers.edit', $register) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $timeRegisters->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection