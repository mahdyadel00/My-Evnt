<section id="sidebar">
    <a href="{{ route('home') }}" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Event</span>
    </a>
    <ul class="side-menu top">
        <li >
            <a href="{{ route('my_tickets') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">My Tickets</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my_wishlist') }}">
                <i class='bx bxs-heart'></i>
                <span class="text">Wishlist</span>
            </a>
        </li>
        <li>
            <a href="{{ route('mailing_list') }}">
                <i class='bx bxs-envelope'></i>
                <span class="text">Mailing List</span>
            </a>
        </li>
        <li class="active">
            <a href="{{ route('profile') }}">
                <i class='bx bxs-user-account'></i>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ route('support') }}">
                <i class='bx bxs-help-circle'></i>
                <span class="text">Support</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu logout">
        <li>
            <a href="{{ route('logout') }}" class="logout">
                <i class='bx bx-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>
