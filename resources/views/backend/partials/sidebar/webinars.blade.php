{{-- Webinars Management Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Webinars Management</span>
</li>

<!--Webinars -->
@can('view webinars')
    <li class="menu-item {{ request()->is('admin/webinars*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-calendar-event"></i>
            <div data-i18n="Webinars">Webinars</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/webinars*') ? 'active' : '' }}">
                <a href="{{ route('admin.webinars.index') }}" class="menu-link">
                    <div data-i18n="Webinars">Webinars</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--About Webinars-->
@can('view about_webinars')
    <li class="menu-item {{ request()->is('admin/about_webinars*') ? 'active' : '' }}">
        <a href="{{ route('admin.about_webinars.index') }}" class="menu-link">
            <div data-i18n="About Webinars">About Webinars</div>
        </a>
    </li>
@endcan

<!--Webinar Speakers-->
@can('view webinar_speakers')
    <li class="menu-item {{ request()->is('admin/webinar_speakers*') ? 'active' : '' }}">
        <a href="{{ route('admin.webinar_speakers.index') }}" class="menu-link">
            <div data-i18n="Webinar Speakers">Webinar Speakers</div>
        </a>
    </li>
@endcan

<!--Webinar Cards-->
@can('view webinar_cards')
    <li class="menu-item {{ request()->is('admin/webinar_cards*') ? 'active' : '' }}">
        <a href="{{ route('admin.webinar_cards.index') }}" class="menu-link">
            <div data-i18n="Webinar Cards">Webinar Cards</div>
        </a>
    </li>
@endcan