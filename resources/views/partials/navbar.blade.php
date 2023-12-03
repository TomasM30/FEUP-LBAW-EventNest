<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventNest</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href=" {{ route('events') }}">Events</a>
                </li>
                @if(!(Auth::user()->isAdmin()))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.events', ['id' => Auth::user()->id]) }}">MyEvents</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.notifications', ['id' => auth()->user()->id]) }}">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                @endif
                <li class="nav-item">
                </li>
            </ul>
            <div class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" style="color: white;">logout</a>
            </div>
        </div>
    </div>
</nav>