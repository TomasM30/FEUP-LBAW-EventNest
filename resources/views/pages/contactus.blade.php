@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center" style="margin-bottom: 2em;">Contact Us</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Contacts</h1>
                <p>Phone: +351 912888787</p>
                <p>Email: eventnest@example.com</p>
                <p>Address: Rua Dr. Roberto Frias, 4200-465 Porto, Portugal</p>
        </div>
        <div class="col-md-6">
            <h2>Follow us on social media</h2>
            <a href="https://www.facebook.com/yourfacebookpage" class="btn btn-primary mb-2"><i class="fab fa-facebook"></i> Facebook</a>
            <a href="https://www.instagram.com/yourinstagram" class="btn btn-primary mb-2"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="https://twitter.com/yourtwitter" class="btn btn-primary mb-2"><i class="fab fa-x-twitter"></i> Twitter</a>
        </div>
    </div>
</div>
@endsection