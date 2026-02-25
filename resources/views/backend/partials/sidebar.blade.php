<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" data-bg-class="bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">    <div class="app-brand demo">
        <a href="{{ route('admin.home') }}" class="app-brand-link">
            <span class="app-brand-logo demo p-5">
                @foreach ($setting->media as $media)
                    @if ($media->name == 'header_logo')
                        <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 80px; height: 50px;">
                    @endif
                @endforeach
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ps ps--active-y">
        {{-- Dashboard Section --}}
        @include('backend.partials.sidebar.dashboard')
        
        {{-- Location Management Section --}}
        @include('backend.partials.sidebar.location')
        
        {{-- Users Management Section --}}
        @include('backend.partials.sidebar.users')
        
        {{-- Content Management Section --}}
        @include('backend.partials.sidebar.content')
        
        {{-- Events Management Section --}}
        @include('backend.partials.sidebar.events')
        {{-- Webinars Management Section --}}
        @include('backend.partials.sidebar.webinars')
        
        {{-- Marketing & Media Section --}}
        @include('backend.partials.sidebar.marketing')
        
        {{-- Settings Section --}}
        @include('backend.partials.sidebar.settings')
    </ul>
</aside>
