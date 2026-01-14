@extends('layouts.app')

@section('content')

<div name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile') }}
    </h2>
    <a href="{{ route('profile.edit') }}"><button>Edit Profile</button></a>
</div>

@endsection