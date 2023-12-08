@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg bg-primary px-3" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventNest</a>
        <button class="navbar-toggler" type="button" id="navbarToggler" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <div class="d-flex ml-auto">
                <button id='login' type="submit" name="action" value="login" class="btn btn-secondary mr-2" type="submit">Login</button>
                <button id='register' type="submit" name="action" value="register" class="btn btn-secondary" type="submit">Register</button>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid row">
    <div id="Information" class="col-md-6 mt-4" style="text-align: justify;">
        <h2>Unleash the Power of Community!</h2>
        <p class="lead">Welcome to EventNest, the ultimate destination for seamlessly connecting and empowering sports communities around the globe! Our user-friendly event management platform is driven by the mission to foster inclusivity, transcending financial barriers and geographical constraints. Whether you' re an avid sports enthusiast, an event organizer, or just looking to explore exciting activities, EventNest is your go-to hub.</p>
        <br>
        <p class="lead">At EventNest, we've curated a dynamic environment where users can effortlessly create, discover, and participate in events of various types. From public gatherings to exclusive private affairs, our platform caters to diverse preferences. Events can be free-flowing or ticketed, with or without capacity limitations, offering a spectrum of choices for every community member.</p>
    </div>
    <div id="AuthForms" class="col-md-6">
        <div id="loginFormContainer" style="display: none;">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label mt-4">Email address</label>
                    <input id="email-login" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter email">
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label mt-4">Password</label>
                    <input id="password-login" type="password" class="form-control" name="password" placeholder="Enter password">
                </div>
                <!-- Remember me -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault">
                        Remember me
                    </label>
                </div>
                <button id='login-button' type="submit" class="btn btn-primary mt-3">Login</button>
                <a id='gmail' href="{{ route('google-auth') }}" class="btn btn-primary align-items-center mt-3">
                    <img src="{{ asset('images/web_dark_sq_na@1x.png') }}" alt="Google sign-in" style="width: 20px; height: 20px; margin-right: 5px;">
                    Sign in with Google
                </a>
            </form>
        </div>
        <div id="registerFormContainer" style="display: none;">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name" class="form-label mt-4">Name</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus required placeholder="Enter name">
                </div>
                <div class="form-group">
                    <label for="username" class="form-label mt-4">Username</label>
                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Enter username" required>
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label mt-4">Email</label>
                    <input id="email-register" type="text" class="form-control" name="email" value="{{ old('email') }}" aria-describedby="emailHelp" placeholder="Enter email" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label mt-4">Password</label>
                    <input id="password-register" type="password" class="form-control" name="password" value="{{ old('password') }}" aria-describedby="passwordHelp" placeholder="Enter password" required>
                    <small id="passwordHelp" class="form-text text-muted">Must be bigger then 8 characters.</small>

                </div>
                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password-confirm" class="form-label mt-4">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Enter password again">
                </div>
            <button id='register-button' type="submit" class="btn btn-primary mt-3">Register</button>
            <a id='gmail' href="{{ route('google-auth') }}" class="btn btn-primary align-items-center mt-3">
                <img src="{{ asset('images/web_dark_sq_na@1x.png') }}" alt="Google sign-in" style="width: 20px; height: 20px; margin-right: 5px;">
                Sign in with Google
            </a>
            </form>
        </div>
        @if ($errors->has('email'))
        <div class="alert alert-dismissible alert-danger mt-3">
            <strong>Oh snap!</strong> <a href="#" class="alert-link">{{ $errors->first('email') }}</a> Try submitting again.
        </div>
        @endif
        @if ($errors->has('password'))

        @endif
        @if (session('success'))
        <div class="alert alert-dismissible alert-success mt-3">
            <strong>Well done!</strong> <a href="#" class="alert-link">{{ session('success') }}</a>.
        </div>
        @endif
        @if ($errors->has('name'))

        @endif
        @if ($errors->has('username'))
        <div class="alert alert-dismissible alert-danger mt-3">
            <strong>Oh snap!</strong> <a href="#" class="alert-link">{{ $errors->first('username') }}</a> Try submitting again.
        </div>
        @endif


    </div>
</div>
</div>


@endsection