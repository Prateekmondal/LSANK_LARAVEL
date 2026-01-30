<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="/static/bootstrap-5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/fontawesome/css/all.css">
    <link rel="icon" href="{{ asset('/static/favicon.ico') }}">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <main class="container d-flex flex-column justify-content-center align-items-center">
        @yield('content')
    </main>

    <footer class="bg-light py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Logging Services, Ankleswar. All rights reserved.</p>
        </div>
    </footer>

    <script src="/static/bootstrap-5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>