@extends('layouts.public')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle"></i> Signature Already Provided</h4>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                    <h5>Thank You!</h5>
                    <p>Your signature has already been recorded for this Time Register.</p>
                    <p>No further action is required.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
@endsection