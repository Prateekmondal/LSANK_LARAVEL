@extends('layouts.app')

@section('content')
<div class="container">
    <h1>JCR List</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="mb-3">
        <a href="{{ route('jcr.create') }}" class="btn btn-primary">Create New JCR</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Field Name</th>
                    <th>Well No</th>
                    <th>Job Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jcrs as $jcr)
                <tr>
                    <td>{{ $jcr->id }}</td>
                    <td>{{ $jcr->fieldName }}</td>
                    <td>{{ $jcr->wellNo }}</td>
                    <td>{{ $jcr->jobDate->format('Y-m-d') }}</td>
                    <td>
                        @if($jcr->final_submit)
                            <span class="badge bg-success">Submitted</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </td>
                    <td>
                        @if($jcr->final_submit)
                            <a href="{{ route('jcr.show', $jcr->id) }}" class="btn btn-info btn-sm">View</a>
                        @else
                            <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('jcr.preview', $jcr->id) }}" class="btn btn-primary btn-sm">Preview</a>
                        @endif
                        
                        <form action="{{ route('jcr.destroy', $jcr->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $jcrs->links() }}
</div>
@endsection