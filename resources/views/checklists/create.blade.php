@extends('layouts.app')

@section('title', 'Create ' . $title)

@section('content')
<div class="container">
    <div class="card w-75">
        <div class="card-header">
            <h2>Create {{ $title }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('checklists.store', $type) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    @include('checklists.partials.header-type-' . $type)
                </div>

                <div class="checklist-items mb-4">
                    <h4 class="mb-3">Checklist Items</h4>
                    @include('checklists.partials.type-' . $type)
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Submit Checklist
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection