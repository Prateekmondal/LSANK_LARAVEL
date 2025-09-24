@extends('layouts.app')

@section('title', 'Admin Edit Checklist')

@section('content')
<div class="container">
    <div class="card w-75">
        <div class="card-header bg-danger text-white">
            <h2><i class="bi bi-shield-lock"></i> Admin Edit Checklist</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Warning:</strong> You are editing this checklist as an administrator. 
                This action will be logged and all changes will be recorded.
            </div>

            <form action="{{ route('checklists.force-update', $checklist->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="well_name" class="form-label">Well Name *</label>
                        <input type="text" class="form-control" id="well_name" name="well_name" 
                               value="{{ $checklist->well_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="{{ $checklist->date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="job_type" class="form-label">Job Type *</label>
                        <input type="text" class="form-control" id="job_type" name="job_type" 
                               value="{{ $checklist->job_type }}" required>
                    </div>
                </div>

                <div class="checklist-items mb-4">
                    <h4 class="mb-3">Checklist Items</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 60%">Item</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 25%">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checklist->checklist_data as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>
                                            <select name="items[{{ $index }}][status]" class="form-select form-select-sm">
                                                <option value="1" {{ $item['status'] ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$item['status'] ? 'selected' : '' }}>No</option>
                                            </select>
                                            <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] }}">
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][comments]" 
                                                   class="form-control form-control-sm" 
                                                   value="{{ $item['comments'] ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="admin_notes" class="form-label">Administrator Notes *</label>
                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" required></textarea>
                    <small class="text-muted">Please explain why you are modifying this completed/signed checklist</small>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-shield-lock"></i> Save Admin Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection