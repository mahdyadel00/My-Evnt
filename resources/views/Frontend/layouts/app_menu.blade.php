<!-- Add this to your footer or wherever you want the menu -->
<article class="mobile-app-menu">
    <div class="menu-app-container">
        @auth
            {{-- For logged in users --}}
            <a href="{{ route('home') }}" class="menu-app-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('events') }}" class="menu-app-item {{ request()->routeIs('events*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar"></i>
                <span>Events</span>
            </a>
            <a href="{{ route('organization') }}" class="menu-app-item {{ request()->routeIs('organization*') ? 'active' : '' }}">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
            </a>
            <a href="{{ route('profile') }}" class="menu-app-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="{{ route('my_wishlist') }}" class="menu-app-item {{ request()->routeIs('my_wishlist*') ? 'active' : '' }}">
                <i class="fa-solid fa-heart"></i>
                <span>Wishlist</span>
            </a>
        @else
            {{-- For non-logged in users --}}
            <a href="{{ route('home') }}" class="menu-app-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('events') }}" class="menu-app-item {{ request()->routeIs('events*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar"></i>
                <span>Events</span>
            </a>
            <a href="{{ route('organization') }}" class="menu-app-item {{ request()->routeIs('organization*') ? 'active' : '' }}">
                <i class="fas fa-plus"></i>
                <span>Create Event</span>
            </a>
            <a href="{{ route('login') }}" class="menu-app-item {{ request()->routeIs('login*') ? 'active' : '' }}">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </a>
            <a href="{{ route('register') }}" class="menu-app-item {{ request()->routeIs('register*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-plus"></i>
                <span>Sign up</span>
            </a>
        @endauth
    </div>
</article>