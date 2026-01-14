@extends('layouts.public')

@section('title', 'Service Unavailable')

@section('content')
    @include('errors._base', [
        'code' => 503,
        'title' => 'Service unavailable',
        'message' => 'The service is temporarily unavailable due to maintenance or high load. Please try again later.',
        'color' => 'text-muted',
        'primary' => ['url' => url('/'), 'label' => 'Back to home'],
        'secondary' => ['url' => 'mailto:' . (config('mail.from.address') ?? 'webmaster@localhost'), 'label' => 'Report outage'],
    ])
@endsection
