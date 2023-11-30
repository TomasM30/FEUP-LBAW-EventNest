<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="/events"><img src="{{ asset('images/Logo.png') }}" alt="EventNest" id='logonav'></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mt-auto">
            @auth
                <li class="nav-item">
                    <a class="btn btn-custom btn-block" href="{{ route('events') }}">Events</a>                
                </li>
                @if(!(Auth::user()->isAdmin()))
                    <li class="nav-item">
                        <a class="btn btn-custom btn-block" href="{{ route('user.events', ['id' => auth()->user()->id]) }}">My Events</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="btn btn-custom btn-block" href="{{ route('user.notifications', ['id' => auth()->user()->id]) }}">Profile</a>
                </li>
                @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="btn btn-custom btn-block" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="btn btn-custom btn-block" href="{{ route('logout') }}">Logout</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>