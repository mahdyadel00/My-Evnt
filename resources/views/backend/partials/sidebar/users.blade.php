{{-- Users Management Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Users Management</span>
</li>

<!--Users-->
@can('view user')
    <li class="menu-item {{ request()->is('admin/users*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users"></i>
            <div data-i18n="Users">Users</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <div data-i18n="Users">Users</div>
                </a>
            </li>
            <li class="menu-item  {{ request()->is('admin/users/create') ? 'active' : '' }}">
                <a href="{{ route('admin.users.create') }}" class="menu-link">
                    <div data-i18n="Add User">Add User</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--Company-->
@can('view company')
    <li class="menu-item {{ request()->is('admin/companies*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building-community"></i>
            <div data-i18n="Company">Company</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/companies*') ? 'active' : '' }}">
                <a href="{{ route('admin.companies.index') }}" class="menu-link">
                    <div data-i18n="Companies">Companies</div>
                </a>
            </li>
            <li class="menu-item  {{ request()->is('admin/companies/create') ? 'active' : '' }}">
                <a href="{{ route('admin.companies.create') }}" class="menu-link">
                    <div data-i18n="Add Company">Add Company</div>
                </a>
            </li>
        </ul>
    </li>
@endcan
