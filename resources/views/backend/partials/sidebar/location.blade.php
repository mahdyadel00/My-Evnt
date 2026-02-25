{{-- Location Management Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Location Management</span>
</li>

<!--Countries-->
@can('view country')
    <li class="menu-item {{ request()->is('admin/countries*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-world"></i>
            <div data-i18n="Countries">Countries</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/countries*') ? 'active' : '' }}">
                <a href="{{ route('admin.countries.index') }}" class="menu-link">
                    <div data-i18n="Countries">Countries</div>
                </a>
            </li>
            <li class="menu-item  {{ request()->is('admin/countries/create') ? 'active' : '' }}">
                <a href="{{ route('admin.countries.create') }}" class="menu-link">
                    <div data-i18n="Add Country">Add Country</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--Cities-->
@can('view city')
    <li class="menu-item {{ request()->is('admin/cities*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-building"></i>
            <div data-i18n="Cities">Cities</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/cities*') ? 'active' : '' }}">
                <a href="{{ route('admin.cities.index') }}" class="menu-link">
                    <div data-i18n="Cities">Cities</div>
                </a>
            </li>
        </ul>
    </li>
@endcan
