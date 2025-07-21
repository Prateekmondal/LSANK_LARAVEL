<header class="container-fluid">
    <nav class="navbar navbar-light navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand mr-0 mb-0 h3" href="/">
                <img src="/static/images/ongc.png" class="d-inline-block align-middle" alt="" height="50rem">
                Logging Services, Ankleswar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('jcr.add') }}">Add JCR</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Checklists
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Checklist-A</a></li>
                            <li><a class="dropdown-item" href="#">Checklist-B</a></li>
                            <li><a class="dropdown-item" href="#">Checklist-C</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Source Checklist</a></li>
                        </ul>
                    </li>
                </ul>
                @if (Route::has('login'))
                    @auth
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="{{ Storage::url('images/profile_image/'.Auth::user()->avatar) }}" alt="User" class="user-avatar me-1" style="width: 2rem; height: 2rem; border-radius:50%;">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (auth()->user()->hasRole('super-admin'))
                                <li><a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}">Admin</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Account</a></li>
                                <li><a class="dropdown-item" href="{{ route('jcr.view') }}">View JCR</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></span>
        
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                </a></li>
                            </ul>
                        </ul>
                    @else
                        <span class="navbar-text"><a class="nav-link" href="{{ route('login') }}">Login</a></span>
                        @if (Route::has('login'))
                            <span class="navbar-text"><a class="nav-link" href="{{ route('register') }}">Register</a></span>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>
</header>