!-- Differentiate buttons using the name attribute -->
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
            <button id='gmail' type="submit" name="action" value="gmail" class="btn btn-custom btn-block">
                <img id='gmail_image' src="{{ asset('images/Gmail.png') }}" class="img-fluid">
            </button>
        </div>
    </div>