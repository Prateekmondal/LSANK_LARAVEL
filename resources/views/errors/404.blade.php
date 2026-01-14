@extends('layouts.public')

@section('title', 'Page not found')

@section('content')
    @include('errors._base', [
        'code' => 404,
        'title' => 'Page not found',
        'message' => "Sorry — the page you were looking for doesn't exist or has been moved.",
        'color' => 'text-danger',
        'primary' => ['url' => url('/'), 'label' => 'Go to homepage'],
        'secondary' => ['url' => 'mailto:' . (config('mail.from.address') ?? 'webmaster@localhost'), 'label' => 'Report this issue'],
    ])
@endsection
