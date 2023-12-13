<nav class="navbar navbar-expand-lg bg-primary sticky-top px-3" data-bs-theme="dark" style="top: 0; z-index: 100;">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('events') }}">EventNest</a>
        <button class="navbar-toggler" type="button" id="navbarToggler" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-lg-flex justify-content-lg-between" id="navbarColor01">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href=" {{ route('events') }}">Events</a>
                </li>
                @if(!(Auth::user()->isAdmin()))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.events', ['id' => Auth::user()->id]) }}">MyEvents</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('user.notifications', ['id' => auth()->user()->id]) }}">
                        Notifications
                        @if($notificationsCount > 0)
                        <span class="badge bg-danger ms-2">{{ $notificationsCount }}</span>
                        @endif
                    </a>
                </li>
                @if(!(Auth::user()->isAdmin()))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.profile', ['id' => Auth::id()]) }}">Profile</a>
                </li>
                @endif
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href=" {{ route('about') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" {{ route('contactus') }}">Contact Us</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" style="color: white;">logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>