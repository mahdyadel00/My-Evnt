<section id="sidebar">
    <a href="{{ route('home') }}" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">MyEvnt</span>
    </a>
    <ul class="side-menu top">
        <li class="{{ request()->routeIs('create_event') ? 'active' : '' }}">
            <a href="{{ route('create_event') }}">
                <i class='bx bxs-plus-circle'></i>
                <span class="text">Create New Event</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('organization.my_events') ? 'active' : '' }}">
            <a href="{{ route('organization.my_events') }}">
                <i class='bx bxs-wallet-alt'></i>
                <span class="text">My Events</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('organization_profile') ? 'active' : '' }}">
            <a href="{{ route('organization_profile') }}">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('organization.archived_events') ? 'active' : '' }}">
            <a href="{{ route('organization.archived_events') }}">
                <i class='bx bxs-archive'></i>
                <span class="text">Archived Events</span>
            </a>
        </li>
        <li>
            <a href="{{ route('contacts') }}">
                <i class='bx bxs-chat'></i>
                <span class="text">Support</span>
            </a>
        </li>

    </ul>
    <ul class="side-menu logout">
        <li>
            <a href="{{ route('organization_logout') }}" class="logout">
                <i class='bx bx-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>
