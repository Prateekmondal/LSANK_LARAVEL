@extends('layouts.app')

@section('title', 'Edit ' . $title)

@section('content')
<div class="container">
    <div class="card w-75">
        <div class="card-header">
            <h2>Edit {{ $title }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.update', $checklist->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    @include('checklists.partials.header-type-' . $type)
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

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Checklist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection