{{-- Settings Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Settings</span>
</li>

@can('view setting')
    <li class="menu-item {{ request()->is('admin/settings*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-settings"></i>
            <div data-i18n="Settings">Settings</div>
        </a>
        <ul class="menu-sub">
            <!--settings-->
            <li class="menu-item {{ request()->is('admin/settings/edit') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit') }}" class="menu-link">
                    <div data-i18n="Settings">Settings</div>
                </a>
            </li>
            @can('view slider')
                <li class="menu-item {{ request()->is('admin/sliders*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sliders.index') }}" class="menu-link">
                        <div data-i18n="Sliders">Sliders</div>
                    </a>
                </li>
            @endcan
            <!--organization slider-->
            <li class="menu-item {{ request()->is('admin/organization-sliders*') ? 'active' : '' }}">
                <a href="{{ route('admin.organization_sliders.edit') }}" class="menu-link">
                    <div data-i18n="Organization Sliders">Organization Sliders</div>
                </a>
            </li>
            <!--currency-->
            <li class="menu-item {{ request()->is('admin/currencies*') ? 'active' : '' }}">
                <a href="{{ route('admin.currencies.index') }}" class="menu-link">
                    <div data-i18n="Currencies">Currencies</div>
                </a>
            </li>
            <!--coupons-->
            <li class="menu-item {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                <a href="{{ route('admin.coupons.index') }}" class="menu-link">
                    <div data-i18n="Coupons">Coupons</div>
                </a>
            </li>
            <!--terms and conditions-->
            <li class="menu-item {{ request()->is('admin/terms-and-conditions/edit') ? 'active' : '' }}">
                <a href="{{ route('admin.terms-condition.edit') }}" class="menu-link">
                    <div data-i18n="Terms & Conditions">Terms & Conditions</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--Communication Section-->
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Communication</span>
</li>

<!--Contact Us-->
<li class="menu-item {{ request()->is('admin/contact-us*') ? 'active' : '' }}">
    <a href="{{ route('admin.contacts.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-mail"></i>
        <div data-i18n="Contact Us">Contact Us</div>
    </a>
</li>

<!--Send Whatsapp-->
<li class="menu-item {{ request()->is('admin/send-whatsapp*') ? 'active' : '' }}">
    <a href="{{ route('admin.send.whatsapp') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-brand-whatsapp"></i>
        <div data-i18n="Send Whatsapp">Send Whatsapp</div>
    </a>
</li>

<!--subscription-->
<li class="menu-item {{ request()->is('admin/subscriptions*') ? 'active' : '' }}">
    <a href="{{ route('admin.subscriptions') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-bell"></i>
        <div data-i18n="Subscriptions">Subscriptions</div>
    </a>
</li>
<!--Session Management-->
<li class="menu-item {{ request()->is('admin/session-management*') ? 'active' : '' }}">
    <a href="{{ route('admin.session-management') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-clock"></i>
        <div data-i18n="Session Management">Session Management</div>
    </a>
</li>
