<footer class="footer bg-primary" style="padding: 10px; margin-top: 20px;">
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6">
                <h1 style="color: white;">EventNest</h1>
                <p style="color: white;">Email: eventnest@example.com</p>
                <p style="color: white;">Phone: +351 912888787</p>
            </div>
            <div class="col-md-6">
                <h4 style="color: white;">Quick Links</h4>
                <div class="row">
                    <div class="col-md-6">
                        <ul style="list-style-type: none; color: white;">
                            <li><a style="color: white;" href="{{ route('events') }}">Events</a></li>
                            <li><a style="color: white;" href="{{ route('about') }}">AboutUs</a></li>
                            <li><a style="color: white;" href="/page3">ContactUs</a></li>
                    </div>
                    <div class="col-md-6">
                        <ul style="list-style-type: none; color: white;">
                            @if(!(Auth::user()->isAdmin()))
                            <li><a style="color: white;" href="{{ route('user.profile', ['id' => Auth::id()]) }}">Profile</a></li>
                            @endif
                            @if(!(Auth::user()->isAdmin()))
                            <li><a style="color: white;" href="{{ route('user.events', ['id' => Auth::user()->id]) }}">MyEvents</a></li>
                            @endif
                            @if(!(Auth::user()->isAdmin()))
                            <li><a style="color: white;" href="{{ route('user.notifications', ['id' => auth()->user()->id]) }}">Notifications</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>