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

    <title>My Tickets</title>
</head>

<body>
<!-- SIDEBAR -->
<section id="sidebar">
    <a href="{{ route('home') }}" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Event</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
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
        <!-- breadcrumb  -->
        <div class="head-title" style="margin-bottom: 20px;">
            <div class="left">
                <ul class="breadcrumb" style="margin-bottom: 20px;">
                    <li>
                        <a class="active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a href="{{ route('my_tickets') }}">My Tickets</a>
                    </li>
                </ul>
                <h1>My Tickets</h1>
                <p>All tickets you have purchased are displayed here. You can scan tickets and send by e-mail. </p>
            </div>
        </div>
        <!-- breadcrumb  -->

        <div class="parent-table">
            <!-- table -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3> Tickets</h3>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <th>Ticket Image</th>
                            <th>Ticket Name</th>
                            <th>Ticket Date</th>
                            <th>Price</th>
                            <th>Format</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(auth()->user() ? auth()->user()->userTickets->count() > 0 : '')
                            @foreach(auth()->user()->userTickets as $ticket)
                                <tr>
                                    <td>
<!--                                      -->
                                    </td>
                                    <td>{{ $ticket->ticket_type }}</td>
                                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                    <td>{{ $ticket->price }} {{ $ticket->currency?->code }}</td>
                                    <td>
                                        <span class="status completed">
                                            @if($ticket->event->format == 'online')
                                                Online
                                            @else
                                                Offline
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- pagination -->
            <div aria-label="Page navigation example">
                <ul class="pagination">
                @if(auth()->user()->events->count() > 0)
                    {{ auth()->user()->userTickets->links() }}
                @endif
                </ul>
            </div>
        </div>


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
