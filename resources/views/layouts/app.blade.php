@include('layouts.header')
@include('layouts.navigation')
<div class="mt-3">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @yield('content')
</div>
@include('layouts.footer')