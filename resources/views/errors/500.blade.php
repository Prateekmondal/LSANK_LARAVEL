@extends('layouts.public')

@section('title', 'Server error')

@section('content')
    @include('errors._base', [
        'code' => 500,
        'title' => 'Something went wrong',
        'message' => "We're experiencing an internal server error. Our team has been notified.",
        'color' => 'text-warning',
        'primary' => ['url' => url('/'), 'label' => 'Back to home'],
        'secondary' => ['url' => 'mailto:' . (config('mail.from.address') ?? 'webmaster@localhost'), 'label' => 'Contact support'],
    ])
@endsection
