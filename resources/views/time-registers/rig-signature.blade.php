<!-- resources/views/logging-units/rig-signature.blade.php -->
@extends('layouts.public')

@section('content')
<h1>Rig Representative Signature</h1>

<div class="card w-75">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card-body">
        @include('time-registers._preview')
        
        <h4>Rig Representative Signature Form</h4>
        <form action="{{ route('time-registers.store-rig-signature', $timeRegister->signature_token) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Observations:<small>*</small></label>
                <textarea name="rig_representative_observations" class="form-control" rows="4" required placeholder="1. Whether the Jobs fully carried out.&#10;2. Any other suggestions ______"></textarea>
            </div>

            <div class="form-group">
                <label>Name:<small>*</small></label>
                <input type="text" name="rig_representative_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Designation:<small>*</small></label>
                <input type="text" name="rig_representative_designation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</div>
@endsection