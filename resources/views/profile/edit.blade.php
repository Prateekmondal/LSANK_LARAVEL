@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column gap-3 my-4">
        <div class="panel-section container">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="panel-section container">
            @include('profile.partials.update-password-form')
        </div>
    </div>
@endsection