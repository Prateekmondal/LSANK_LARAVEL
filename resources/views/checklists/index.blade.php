@extends('layouts.app')

@section('title', 'My Checklists')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Checklists</h1>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                Filter by Type
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('checklists.index') }}">All</a></li>
                <li><a class="dropdown-item" href="{{ route('checklists.index', ['type' => 'a']) }}">Pre-Departure (A)</a></li>
                <li><a class="dropdown-item" href="{{ route('checklists.index', ['type' => 'b']) }}">On-Site (B)</a></li>
                <li><a class="dropdown-item" href="{{ route('checklists.index', ['type' => 'c']) }}">Upon-Arrival (C)</a></li>
            </ul>
        </div>
    </div>

    <div class="card w-100">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Well Name</th>
                            <th>Date</th>
                            <th>Logging Unit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checklists as $checklist)
                            <tr>
                                <td>{{ $checklist->type_name }}</td>
                                <td>{{ $checklist->well_no }}</td>
                                <td>{{ $checklist->date->format('d/m/Y') }}</td>
                                <td>{{ $checklist->logging_unit_no }}</td>
                                <td>
                                    <span class="badge bg-{{ $checklist->status === 'signed' ? 'success' : ($checklist->status === 'completed' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($checklist->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($checklist->status === 'draft')
                                        <a href="{{ route('checklists.edit', ['checklist' => $checklist->id, 'type' => $checklist->type]) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('checklists.show', $checklist->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No checklists found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end">
                {{ $checklists->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection