<nav class="navbar navbar-expand-lg bg-primary px-3" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/login">EventNest</a>
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

<script>
    document.getElementById('login').addEventListener('click', function() {
        if (window.location.pathname !== '/login') {
            window.location.href = '/login';
        }
    });

    document.getElementById('register').addEventListener('click', function() {
        if (window.location.pathname !== '/login') {
            window.location.href = '/login';
        }
    });
</script>