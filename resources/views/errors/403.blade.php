@extends('layouts.public')

@section('title', 'Forbidden')

@section('content')
    @include('errors._base', [
        'code' => 403,
        'title' => 'Access denied',
        'message' => "You don't have permission to view this page.",
        'color' => 'text-secondary',
        'primary' => ['url' => (url()->previous() ?: url('/')), 'label' => 'Go back'],
        'secondary' => ['url' => 'mailto:' . (config('mail.from.address') ?? 'webmaster@localhost'), 'label' => 'Request access'],
    ])
@endsection
