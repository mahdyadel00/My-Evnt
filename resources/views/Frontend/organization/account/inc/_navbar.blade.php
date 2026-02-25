<nav class="navbar">
    <i class='bx bx-menu'></i>
    <a href="#" class="profile">
        @if(auth()->guard('company')->user()->media->count() > 0)
        @foreach(auth()->guard('company')->user()->media as $media)
        @if($media->name == 'logo')
            <img src="{{ asset('storage/'.$media->path) }}">
        @endif
        @endforeach
        @else
            <img src="{{ asset('Front') }}/EventDash/img/people.png">
        @endif
    </a>
</nav>
