@include('layouts.header')
@include('layouts.navigation')
@if ($errors->any())
    <div class="container text-center">
        @foreach ($errors->all() as $index => $error)
            <small>{{ " " . ($index + 1) . ". " . $error }}</small>
            <br>
        @endforeach
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@yield('content')
@include('layouts.footer')