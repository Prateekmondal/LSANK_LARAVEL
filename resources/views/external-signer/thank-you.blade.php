@extends('layouts.public')

@section('title', 'Thank You')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h2 class="mb-3">Thank You!</h2>
                    <p class="lead">Your signature has been successfully recorded.</p>
                    <p>You may now close this window.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection