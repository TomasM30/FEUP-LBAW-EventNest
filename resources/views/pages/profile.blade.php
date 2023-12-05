<div class="profile">
    <h3>Profile</h3>
    <h4>&#64;{{ $user->username }}</h4>
    <img src="{{ $user->getProfileImage() }}">
</div>
