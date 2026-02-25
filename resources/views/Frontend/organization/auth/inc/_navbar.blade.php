<nav class="navbar">
    <i class='bx bx-menu'></i>
    <a href="{{ route('profile') }}" class="profile">
        @if(auth()->guard('company')->user()->media->count() > 0)
            @foreach(auth()->guard('company')->user()->media as $media)
                <img src="{{ asset('storage/'.$media->path) }}" alt="" style="width: 50px;height: 50px;">
            @endforeach
        @else
            <img src="{{ asset('Front') }}/img/profile.png" alt="">
        @endif
    </a>
</nav>
