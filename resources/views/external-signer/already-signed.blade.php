@extends('layouts.public')

@section('title', 'Already Signed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-circle text-warning fa-5x"></i>
                    </div>
                    <h2 class="mb-3">Already Signed</h2>
                    <p class="lead">This checklist has already been signed.</p>
                    <p>Thank you for your participation.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection