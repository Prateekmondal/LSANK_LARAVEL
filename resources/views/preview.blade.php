@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>JCR Preview - {{ $jcr->fieldName }} - {{ $jcr->wellNo }}</h2>
            <p class="mb-0">Please review all details before final submission</p>
        </div>
        <div class="card-body">
            @include('jcr._preview_content')
            
            <div class="mt-4">
                <form action="{{ route('jcr.submit', $jcr->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle"></i> Submit Final JCR
                    </button>
                </form>
                
                <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-lg ml-2">
                    <i class="fas fa-edit"></i> Edit JCR
                </a>
                
                <a href="{{ route('jcr.index') }}" class="btn btn-secondary btn-lg ml-2">
                    <i class="fas fa-save"></i> Save as Draft
                </a>
            </div>
        </div>
    </div>
</div>
@endsection