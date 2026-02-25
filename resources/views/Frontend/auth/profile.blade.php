<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('Front') }}/EventDash/css/dashboard.css">

    <title>Profile</title>
</head>

<body>


<!-- SIDEBAR -->
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
{{--        <li>--}}
{{--            <a href="{{ route('mailing_list') }}">--}}
{{--                <i class='bx bxs-envelope'></i>--}}
{{--                <span class="text">Mailing List</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="active">
            <a href="{{ route('profile') }}">
                <i class='bx bxs-user-account'></i>
                <span class="text">Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ route('contacts') }}">
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
<!-- SIDEBAR -->


<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    <nav class="navbar">
        <i class='bx bx-menu'></i>
        <a href="{{ route('profile') }}" class="profile">
            @if(auth()->user()->media->isNotEmpty())
                @foreach(auth()->user()->media as $media)
                        <img src="{{ asset('storage/'.$media->path) }}" alt="">
                @endforeach
            @else
                <img src="{{ asset('Front') }}/img/profile.png" alt="">
            @endif
        </a>
    </nav>
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <div class="head-title" style="margin-bottom: 20px;">
            <div class="left">
                <ul class="breadcrumb" style="margin-bottom: 20px;">
                    <li>
                        <a class="active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a href="{{ route('profile') }}">Profile</a>
                    </li>
                </ul>
                <h1>Profile</h1>
                <p>Add information to your profile to make ticket purchases easier and faster </p>
            </div>
        </div>
        @include('Frontend.layouts._message')
        <!-- start profile data -->
        <section class="py-xl-8">
            <h3 class="p-1" style="padding: 0 10px;">Account Settings</h3>
            <div class="container">
                <div class="row gy-4 gy-lg-0">
                    <div class="col-12 col-lg-12 col-xl-12">
                        <div class="card widget-card border-light shadow-sm">
                            <div class="card-body p-3">
                                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                                data-bs-target="#overview-tab-pane" type="button" role="tab" aria-controls="overview-tab-pane"
                                                aria-selected="true">General Information</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                                data-bs-target="#password-tab-pane" type="button" role="tab" aria-controls="password-tab-pane"
                                                aria-selected="false">Change Password</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete-tab-pane"
                                                type="button" role="tab" aria-controls="delete-tab-pane" aria-selected="false"
                                                style="color: red;">Delete Account</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-4" id="profileTabContent">
                                    <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel"
                                         aria-labelledby="overview-tab" tabindex="0">
                                        <form action="{{ route('update_profile') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <h5 class="pb-2">Personal Information</h5>
                                            <div class="row gy-3 gy-xxl-4">
                                                <div class="col-12 col-md-6">
                                                    <!-- <label for="firstName" class="form-label">First Name</label> -->
                                                    <input type="text" class="form-control" id="first_name" placeholder="First Name" value="{{ auth()->user()->first_name }}" name="first_name">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" class="form-control" id="last_name" placeholder="Last Name" value="{{ auth()->user()->last_name }}" name="last_name">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="email" class="form-control" id="email" placeholder="example@gmail.com" value="{{ auth()->user()->email }}" name="email">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="tel" class="form-control" id="phone" placeholder="Phone Number" value="{{ auth()->user()->phone }}" name="phone">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" class="form-control" id="address" placeholder="Address" value="{{ auth()->user()->address }}" name="address">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="date" class="form-control" id="birth_date" placeholder="Birth Date" value="{{ auth()->user()->birth_date }}" name="birth_date">
                                                </div>
                                            </div>
                                            <div class="row gy-3 gy-xxl-4">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab"
                                         tabindex="0">
                                        <form action="{{ route('change_password') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row gy-3 gy-xxl-4">
                                                <div class="col-12 col-md-6">
                                                    <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Current Password">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="password" class="form-control" id="newPassword" name="password" placeholder="New Password">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password">
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="delete-tab-pane" role="tabpanel" aria-labelledby="delete-tab"
                                         tabindex="0">
                                        <form action="{{ route('delete_account') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="row gy-3 gy-xxl-4">
                                                <div class="col-12 text-center">
                                                    <p>Are you sure you want to delete your account? <br> This action cannot be undone.</p>
                                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end profile data -->
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->
<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('Front') }}/EventDash/js/script.js"></script>
</body>

</html>
