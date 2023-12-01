@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div id='left' class='column'>
            <img src="{{ asset('images/Logo.png') }}" alt="EventNest" class="img-fluid" id='logo'>
            <p class="text-md" id="catch-frase1">
                Unleash the Power of Community: Where Every Passion Finds Its Event. Join, Create, and Thrive with EventNest. - Your Gateway to Inclusive Sports Experiences!
            </p>
            <hr>
            <p class="text-md" id="information1">
                Welcome to EventNest, the ultimate destination for seamlessly connecting and empowering sports communities around the globe! Our user-friendly event management platform is driven by the mission to foster inclusivity, transcending financial barriers and geographical constraints. Whether you' re an avid sports enthusiast, an event organizer, or just looking to explore exciting activities, EventNest is your go-to hub. </p>
            <p class="text-md" id="information2">
                At EventNest, we've curated a dynamic environment where users can effortlessly create, discover, and participate in events of various types. From public gatherings to exclusive private affairs, our platform caters to diverse preferences. Events can be free-flowing or ticketed, with or without capacity limitations, offering a spectrum of choices for every community member.
            </p>
        </div>
        <div id='right' class='column'>
            <img id='textlogo' src="{{ asset('images/TextLogoGreen.png') }}" alt="EventNest" class="img-fluid">
            <h3 id='catch-frase2' class="text-md">Unleash the Power of Community</h3>
            <!-- Differentiate buttons using the name attribute -->
            <button id='login' type="submit" name="action" value="login" class="btn btn-custom btn-block">Login</button>
            <div id="loginFormContainer" style="display: none;">
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <label for="email">E-mail</label>
                    <input id="email-login" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="error">
                          {{ $errors->first('email') }}
                        </span>
                    @endif

                    <label for="password" >Password</label>
                    <input id="password-login" type="password" name="password" required>
                    @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif

                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>

                    <button id='login-button' type="submit" class="btn btn-custom btn-block">
                        Login
                    </button>
                    @if (session('success'))
                        <p class="success">
                            {{ session('success') }}
                        </p>
                    @endif
                </form>

            </div>
            <button id='register' type="submit" name="action" value="register" class="btn btn-custom btn-block">Register</button>
            <div id="registerFormContainer" style="display: none;">
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                      <span class="error">
                          {{ $errors->first('name') }}
                      </span>
                    @endif

                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                    @if ($errors->has('username'))
                      <span class="error">
                          {{ $errors->first('username') }}
                      </span>
                    @endif

                    <label for="email">E-Mail Address</label>
                    <input id="email-register" type="email" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                      <span class="error">
                          {{ $errors->first('email') }}
                      </span>
                    @endif

                    <label for="password">Password</label>
                    <input id="password-register" type="password" name="password" required>
                    @if ($errors->has('password'))
                      <span class="error">
                          {{ $errors->first('password') }}
                      </span>
                    @endif

                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required>
                    <button id='register-button' type="submit" class="btn btn-custom btn-block">Register</button>
                </form>

            </div>
            <a id='gmail' href="{{ route('google-auth') }}" class="btn btn-custom btn-block">
                <img id='gmail_image' src="{{ asset('images/Gmail.png') }}" class="img-fluid">
            </a>
        </div>
    </div>
</div>
@endsection