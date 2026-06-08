<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $title ?? 'LS, Ank' ?>
    </title>
    <!-- jquery -->
    <script src="{{ global_asset('/static/ajax/jquery.js') }}"
        integrity="sha512-nO7wgHUoWPYGCNriyGzcFwPSF+bPDOR+NvtOYy2wMcWkrnCNPKBcFEkU80XIN14UVja0Gdnff9EmydyLlOL7mQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ global_asset('/static/bootstrap-5.2.1/dist/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ global_asset('/static/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ global_asset('/static/css/select2.min.css') }}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ global_asset('/static/fontawesome/css/fontawesome.css') }}">
    <script defer src="{{ global_asset('/static/fontawesome/js/all.js') }}"></script>

    <!-- Tempus Dominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ global_asset('/static/css/tempusdominus-bootstrap-4.min.css') }}"
        integrity="sha256-XPTBwC3SBoWHSmKasAk01c08M6sIA5gF5+sRxqak2Qs=" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ global_asset('/static/css/jquery-ui.css') }}">
    <script src="{{ global_asset('/static/js/jquery-ui.js') }}"></script>

    <link rel="stylesheet" href="{{ global_asset('/static/css/main.css') }}">
    @stack('css')

    @stack('pagecss')
    @stack('pagejs')
        
    <link rel="icon" href="{{ global_asset('/static/favicon.ico') }}">
</head>

<body>