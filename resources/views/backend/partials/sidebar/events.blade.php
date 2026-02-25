{{-- Events Management Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Events Management</span>
</li>

<!--Events -->
@can('view event')
    <li class="menu-item {{ request()->is('admin/events*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-calendar-event"></i>
            <div data-i18n="Events">Events</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/events*') ? 'active' : '' }}">
                <a href="{{ route('admin.events.index') }}" class="menu-link">
                    <div data-i18n="Events">Events</div>
                </a>
            </li>
            <!--event sold out-->
            @can('view sold_out')
                <li class="menu-item  {{ request()->is('admin/sold_out') ? 'active' : '' }}">
                    <a href="{{ route('admin.sold_out') }}" class="menu-link">
                        <div data-i18n="Event Sold Out">Event Sold Out</div>
                    </a>
                </li>
            @endcan
        </ul>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/events/send-sms-invitation') ? 'active' : '' }}">
                <a href="{{ route('admin.events.send-sms-invitation') }}" class="menu-link">
                    <div data-i18n="Send SMS Invitation">Send SMS Invitation</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--Orders-->
@can('view orders')
    <li class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
        <a href="{{ route('admin.orders.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
            <div data-i18n="Orders">Orders</div>
        </a>
    </li>
@endcan

<!--Event Report For Company Module-->
@can('view reports')
    <li class="menu-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
        <a href="{{ route('admin.reports.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-chart-bar"></i>
            <div data-i18n="Report">Report</div>
        </a>
    </li>
@endcan
