@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h1>Welcome to EventNest</h1>
                    <p>Empowering Sports Communities</p>
                </div>
                <div class="card-body">

                    <section>
                        <h2>Our Mission</h2>
                        <p>At EventNest, our mission is to foster vibrant sports communities by providing a user-friendly event management platform. We believe in inclusivity, welcoming everyone regardless of financial status or location. Our user-maintained platform thrives on community collaboration, allowing users to create and participate in a diverse array of events.</p>
                    </section>

                    <section>
                        <h2>Event Classification</h2>
                        <p>Events on EventNest are classified into three categories: public, private, or approval-required. They can also be ticketed or free, with the option for capacity limitations or no limitations.</p>
                    </section>

                    <section>
                        <h2>User Types and Permissions</h2>
                        <p>We cater to four distinct user types, each with varying permissions:</p>
                        <ul>
                            <li><strong>Guests:</strong> Limited access, able to view the welcome page, and sign-in/sign-up pages only.</li>
                            <li><strong>Authenticated Users:</strong> Can view event details, use search tools, create and join events, add events to favorites, and receive event notifications.</li>
                            <li><strong>Verified Users:</strong> Similar capabilities to Authenticated Users but can create paid events. Identified by a trustworthiness indicator, showing their ability to sell event tickets.</li>
                            <li><strong>Admins:</strong> Responsible for platform moderation, handling user reports, and making decisions on event removal. Admins need a standard user account for creating or participating in events.</li>
                        </ul>
                    </section>

                    <section>
                        <h2>User Interactions and Capabilities</h2>
                        <p>Users on EventNest enjoy a range of capabilities based on their roles:</p>
                        <ul>
                            <li><strong>Event Organizers:</strong> Full control over events, including editing details, managing participants, creating polls, adjusting visibility, handling join requests, and canceling events.</li>
                            <li><strong>Verified Users:</strong> Similar to organizers but with the ability to create paid events.</li>
                            <li><strong>Attendees:</strong> Can participate in polls, upload files, access event tickets, view participants, invite others, and leave events.</li>
                        </ul>
                    </section>

                    <section>
                        <h2>Community Engagement</h2>
                        <p>Event participants can comment on events and react to comments. Comment authors have full control, able to edit or delete their comments. There's a dedicated chat room exclusively for event participants.</p>
                    </section>

                    <section>
                        <h2>Platform Revenue Model</h2>
                        <p>We sustain our platform through a commission-based revenue model, earning a 15% commission on each ticket sold. This revenue plays a crucial role in supporting and enhancing the EventNest experience.</p>
                    </section>

                    <section>
                        <h2>Moderation and Support</h2>
                        <p>Users have the option to report issues with events. Admins handle platform moderation, user reports, and event removal decisions. Admins have exclusive access to tools and functionalities for platform supervision, user action moderation, and insights into website statistics.</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection