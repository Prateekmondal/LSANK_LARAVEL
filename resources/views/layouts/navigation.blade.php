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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        JCR
                    </a>
                <ul class="dropdown-menu">
                    @can('create', App\Models\Jcr::class)
                        <li><a class="dropdown-item" href="{{ route('jcr.create') }}">Add JCR</a></li>
                    @endcan
                    <li><a class="dropdown-item" href="{{ route('jcr.index') }}">View JCR</a></li>
                </ul>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Checklists
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/checklists">View Checklists</a></li>
                        @can('create', App\Models\ExplosiveChecklist::class)
                            <li><a class="dropdown-item" href="/checklists/create/a">Checklist-A</a></li>
                            <li><a class="dropdown-item" href="/checklists/create/b">Checklist-B</a></li>
                            <li><a class="dropdown-item" href="/checklists/create/c">Checklist-C</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Time Register
                    </a>
                    <ul class="dropdown-menu">
                        @can('create', App\Models\TimeRegister::class)
                            <li><a class="dropdown-item" href="{{ route('time-registers.create') }}">Create Time Register</a></li>
                        @endcan
                        <li><a class="dropdown-item" href="{{ route('time-registers.index') }}">View Time Registers</a></li>
                    </ul>
                </li>
            </ul>
            @if (Route::has('login'))
                @auth
                    <ul class="navbar-nav">
                        <!-- Add this to your navbar -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <!-- Update the notification dropdown -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <li>
                                        @if(!empty($notification->data['link']))
                                            <a class="dropdown-item" href="{{ route('notifications.read', $notification->id) }}">
                                        @endif
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="bi bi-clipboard-check fs-4 text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $notification->data['message'] }}</div>
                                                    <small
                                                        class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item">No new notifications</span></li>
                                @endforelse
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-center">Mark all as read</button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <img src="{{ Storage::url('images/profile_image/' . Auth::user()->avatar) }}" alt="User"
                                    class="user-avatar me-1" style="width: 2rem; height: 2rem; border-radius:50%;">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (auth()->user()->hasAnyRole(['super-admin', 'Head_Logging_Services', 'Location Manager']))
                                    <li><a class="dropdown-item" target="_blank"
                                            href="{{ route('filament.admin.pages.dashboard') }}">Admin</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Account</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></span>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
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