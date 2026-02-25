<header class="header">
    <div class="container">
        <div class="row v-center">
            <div class="header-item item-left">
                <a href="{{ route('home') }}">
                    @foreach($setting->media as $media)
                        @if($media->name == 'header_logo')
                            <img class="logo" src="{{ asset('storage/'.$media->path) }}" alt="logo">
                        @endif
                    @endforeach
                </a>
            </div>
            <!-- menu start here -->
            <div class="header-item item-center">
                <div class="menu-overlay"></div>
                <nav class="menu">
                    <div class="mobile-menu-head">
                        <a style="padding: 0 50px 0 15px; color: #0E143A; font-size: 20px;font-weight: bold;"
                           href="{{ route('home') }}">Event</a>
                        <div class="go-back"><i class="fa fa-angle-left"></i></div>
                        <div class="current-menu-title"></div>
                        <div class="mobile-menu-close">&times;</div>
                    </div>
                    <ul class="menu-main">
                        <li>
                            <a href="{{ route('organization') }}">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('faq') }}">FAQ</a>
                        </li>
                        <!-- <li>
                           <a href="{{ route('blogs') }}">Blog</a>
                        </li> -->
{{--                        <li class="menu-item-has-children">--}}
{{--                            <a href="#">Benefits <i class="fas fa-angle-down"></i></a>--}}
{{--                            <div class="sub-menu single-column-menu">--}}
{{--                                <ul>--}}
{{--                                    <li><a href="EventConstructor.html">Event Constructor</a></li>--}}
{{--                                    <li><a href="EventManagement.html                      ">Event Management</a></li>--}}
{{--                                    <li><a href="#">Ticket Office</a></li>--}}
{{--                                    <li><a href="#">Experience</a></li>--}}
{{--                                    <li><a href="#">Ticket Scanner</a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item-has-children">--}}
{{--                            <a href="#">Industries <i class="fas fa-angle-down"></i></a>--}}
{{--                            <div class="sub-menu single-column-menu">--}}
{{--                                <ul>--}}
{{--                                    <li><a href="#">Culture</a></li>--}}
{{--                                    <li><a href="#">Dance</a></li>--}}
{{--                                    <li><a href="#">Education</a></li>--}}
{{--                                    <li><a href="#">Experience</a></li>--}}
{{--                                    <li><a href="#">Health</a></li>--}}
{{--                                    <li><a href="#">Music</a></li>--}}
{{--                                    <li><a href="#">Sport</a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">instructions</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="#">Pricing</a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{ route('contacts') }}">Contact us</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- menu end here -->
            <div class="header-item item-right">
                @if(auth()->guard('company')->check())
                <div class="profile">
                    <img src="{{ asset('Front') }}/img/profile.png" alt="">
                    <ul class="profile-link">
                        <li><a href="{{ route('organization_profile') }}"><i class='bx bxs-user-circle icon'></i> My Profile</a></li>
                        @if(auth()->guard('company')->user())
                        <li><a href="{{ route('organization.my_events') }}"><i class='bx bxs-cog'></i> My Events</a></li>
                        @endif
                        <li><a href="{{ route('organization_logout') }}"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                    </ul>
                </div>
                @else
                    <a class="button-nav" href="{{ route('organization_login') }}">Login</a>
                @endif
                <!-- mobile menu trigger -->
                <div class="mobile-menu-trigger">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </div>
    </div>
</header>
