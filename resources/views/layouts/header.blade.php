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
    <script src="/static/ajax/jquery.js"
        integrity="sha512-nO7wgHUoWPYGCNriyGzcFwPSF+bPDOR+NvtOYy2wMcWkrnCNPKBcFEkU80XIN14UVja0Gdnff9EmydyLlOL7mQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap CSS -->
    <link href="/static/bootstrap-5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="/static/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- select2 -->
    <link rel="stylesheet" href="/static/css/select2.min.css"
        integrity="sha512-yI2XOLiLTQtFA9IYePU0Q1Wt0Mjm/UkmlKMy4TU4oNXEws0LybqbifbO8t9rEIU65qdmtomQEQ+b3XfLfCzNaw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/fontawesome/css/fontawesome.css">
    <script defer src="/static/fontawesome/js/all.js"></script>

    <!-- Tempus Dominus Bootstrap 4 -->
    <link rel="stylesheet" href="/static/css/tempusdominus-bootstrap-4.min.css"
        integrity="sha256-XPTBwC3SBoWHSmKasAk01c08M6sIA5gF5+sRxqak2Qs=" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="/static/css/main.css">
    @stack('css')

    @stack('pagecss')
    @stack('pagejs')
    
    <link rel="icon" href="/static/favicon.ico">
</head>

<body>