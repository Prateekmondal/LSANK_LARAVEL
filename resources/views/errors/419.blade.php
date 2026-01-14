@extends('layouts.public')

@section('title', 'Page expired')

@section('content')
    @include('errors._base', [
        'code' => 419,
        'title' => 'Page expired',
        'message' => 'The page has expired due to inactivity. Please try again.',
        'color' => 'text-info',
        'primary' => ['url' => url()->current(), 'label' => 'Retry'],
        'secondary' => ['url' => url('/'), 'label' => 'Go to homepage'],
    ])
@endsection
