<!DOCTYPE html>
<html lang="en">

<head>
    <title>Event Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/style.css" />
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Front') }}/css/swiper-bundle.min.css" />
</head>

<body>
<!-- header start -->
<header class="header" style="background-color: #fff;">
    <div class="container">
        <div class="row v-center">
            <div class="header-item item-left">
                <div class="logo">
                    <a href="{{ route('home') }}">Event</a>
                </div>
            </div>
            <!-- menu start here -->
            <div class="header-item item-center">
                <div class="menu-overlay"></div>
                <nav class="menu">
                    <div class="mobile-menu-head">
                        <a style="padding: 0 50px 0 15px; color: #0E143A; font-size: 20px;font-weight: bold;"
                           href="Newhome.html">Event</a>
                        <div class="go-back"><i class="fa fa-angle-left"></i></div>
                        <div class="current-menu-title"></div>
                        <div class="mobile-menu-close">&times;</div>
                    </div>
                    <ul class="menu-main">
                        <li>
                            <a href="#">Beauty</a>
                        </li>
                        <li>
                            <a href="#">Business</a>
                        </li>
                        <li>
                            <a href="#">Comedy</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">More <i class="fas fa-angle-down"></i></a>
                            <div class="sub-menu single-column-menu">
                                <ul>
                                    <li><a href="#">Culture</a></li>
                                    <li><a href="#">Dance</a></li>
                                    <li><a href="#">Education</a></li>
                                    <li><a href="#">Experience</a></li>
                                    <li><a href="#">Health</a></li>
                                    <li><a href="#">Music</a></li>
                                    <li><a href="#">Sport</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- menu end here -->
            <div class="header-item item-right">
                <a class="button-organizer" href="./pages organizer interface/index.html">For Organizer</a>
                <a class="button-nav" href="login.html">Login</a>
                <div class="profile">
                    <img src="img/profile.png" alt="">
                    <ul class="profile-link">
                        <li><a href="./EventDash/index.html"><i class='bx bxs-user-circle icon'></i> Events</a></li>
                        <li><a href="./EventDash/profile.html"><i class='bx bxs-cog'></i> Profile</a></li>
                        <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                    </ul>
                </div>
                <!-- mobile menu trigger -->
                <div class="mobile-menu-trigger">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end header -->

<!-- start ticket -->
<section class="ticket-section">
    <div class="ticket-data">
        <div class="left">
            <div class="ticket-info">
                <p class="date">
                    <span>{{ date('l', strtotime($event->start_date)) }}</span>
                    <span class="day">
                            {{ date('M', strtotime($event->start_date)) }}
                            {{ date('d', strtotime($event->start_date)) }}
                            {{ date('S', strtotime($event->start_date)) }}
                        </span>
                    <span>{{ date('Y', strtotime($event->start_date)) }}</span>
                </p>
                <div class="ticket-name">
                    <h1>{{ $event->name }}</h1>
                </div>
                <div class="event-time">
                    <p><span>{{ date('h:i A', strtotime($event->start_date)) }}</span> <span>TO</span> <span>{{ date('h:i A', strtotime($event->end_date)) }}</span></p>
                </div>
                <div class="Company-Name"><span>{{ $event->company?->company_name }}</span>
                    <span class="separator"><i class="far fa-smile"></i></span><span class="location">{{ $event->location }}</span>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="right-info-container">
                <div class="show-name logo">
                    @foreach($event->company->media as $media)
                    @if($media->name == 'logo')
                    <img src="{{ asset('storage/'.$media->path) }}" alt="logo">
                    @endif
                    @endforeach
                </div>
                <div class="event-time">
                    <p> <span>{{ date('h:i A', strtotime($event->start_date)) }}</span> <span>TO</span> <span>{{ date('h:i A', strtotime($event->end_date)) }}</span></p>
                    <p>Scan the QR code</p>
                </div>
                <div class="barcode">{!! QrCode::size(100)->generate($event->qr_code) !!}</div>
            </div>
        </div>
    </div>
</section>
<!-- end ticket -->

<!-- Start Footer section -->
<footer class="footer-section">
    <div class="container">
        <div class="footer-cta pt-5 pb-5">
            <div class="row">
                <div class="col-xl-4 col-md-4 mb-30">
                    <div class="single-cta">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="cta-text">
                            <h4>Find us</h4>
                            <span>1010 Avenue, sw 54321, chandigarh</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 mb-30">
                    <div class="single-cta">
                        <i class="fas fa-phone"></i>
                        <div class="cta-text">
                            <h4>Call us</h4>
                            <span><a style="color: #878787;" href="tel:+201033993202">+20 10 3399 3202</a></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 mb-30">
                    <div class="single-cta">
                        <i class="far fa-envelope-open"></i>
                        <div class="cta-text">
                            <h4>Mail us</h4>
                            <span><a style="color: #878787;" href="mailto:info@innoworx.site">mail@info.com </a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-content pt-5 pb-5">
            <div class="row">
                <div class="col-xl-4 col-lg-4 mb-50">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <!-- <a href="index.html"><img src="https://i.ibb.co/QDy827D/ak-logo.png" class="img-fluid" alt="logo"></a> -->
                            <a href="#" style="font-size: 30px;font-weight: 700; color:#fff">Event</a>
                        </div>
                        <div class="footer-text">
                            <p>Lorem ipsum dolor sit amet, consec tetur adipisicing elit, sed do eiusmod tempor incididuntut consec
                                tetur adipisicing
                                elit,Lorem ipsum dolor sit amet.</p>
                        </div>
                        <div class="footer-social-icon">
                            <span>Follow us</span>
                            <a href="#"><i class="fab fa-facebook-f facebook-bg"></i></a>
                            <a href="#"><i class="fab fa-twitter twitter-bg"></i></a>
                            <a href="#"><i class="fab fa-google-plus-g google-bg"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Event Types</h3>
                        </div>
                        <ul>
                            <li><a href="#">Music</a></li>
                            <li><a href="#">Sports</a></li>
                            <li><a href="#">Culture</a></li>
                            <li><a href="#">Business</a></li>
                            <li><a href="#">View All</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Useful Links</h3>
                        </div>
                        <ul>
                            <li><a href="./EventDash/profile.html">My Profile</a></li>
                            <li><a href="./pages organizer interface/FAQS.html">FAQS</a></li>
                            <li><a href="#">Pricing</a></li>
                            <li><a href="./pages organizer interface/Contact_us.html">Contact us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Subscribe</h3>
                        </div>
                        <div class="footer-text mb-25">
                            <p>Don’t miss to subscribe to our new feeds, kindly fill the form below.</p>
                        </div>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="text" placeholder="Email Address">
                                <button><i class="fab fa-telegram-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                    <div class="copyright-text">
                        <p>Copyright &copy; 2024, All Right Reserved <a href="Newhome.html">Event</a></p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                    <div class="footer-menu">
                        <ul>
                            <li><a href="Newhome.html">Home</a></li>
                            <li><a href="./pages organizer interface/terms.html">Terms</a></li>
                            <li><a href="./pages organizer interface/privacy.html">Privacy</a></li>
                            <li><a href="./pages organizer interface/Contact_us.html">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer section -->

<script src="{{ asset('Front') }}/js/bootstrap.js"></script>
<script src="{{ asset('Front') }}/js/bootstrap.min.js"></script>
<script src="{{ asset('Front') }}/js/swiper-bundle.min.js"></script>
<script src="{{ asset('Front') }}/js/script.js"></script>
</body>

</html>
