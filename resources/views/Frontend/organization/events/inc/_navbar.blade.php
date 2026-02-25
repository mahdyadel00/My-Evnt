<nav class="navbar">
    <i class='bx bx-menu'></i>
    <a href="#" class="profile">
        @auth
            @foreach(auth()->user()->media as $media)
                <img src="{{ asset('storage/'.$media->path) }}">
            @endforeach
        @else
            <img src="{{ asset('Front') }}/EventDash/img/people.png">
        @endauth
    </a>
</nav>
