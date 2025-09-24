<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="/static/bootstrap-5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/fontawesome/css/all.css">
</head>
<body class="vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('/static/images/ongc.png') }}" alt="Company Logo" width="40">
            </a>
            <span class="navbar-text">
                Checklist B - External Signer Portal
            </span>
        </div>
    </nav>

    <main class="container" style='height: 75%;'>
        @yield('content')
    </main>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Logging Services, Ankleswar. All rights reserved.</p>
        </div>
    </footer>

    <script src="/static/bootstrap-5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>