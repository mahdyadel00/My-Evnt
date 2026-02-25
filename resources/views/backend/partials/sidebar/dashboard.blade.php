{{-- Dashboard Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Dashboard</span>
</li>

<li class="menu-item {{ request()->routeIs('admin.home') ? 'active' : '' }}">
    <a href="{{ route('admin.home') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Home">Home</div>
    </a>
</li>

<li class="menu-item {{ request()->is('admin/roles*') ? 'active' : '' }}">
    <a href="{{ route('admin.roles.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-shield-check"></i>
        <div data-i18n="Roles & Permissions">Roles & Permissions</div>
    </a>
</li>
