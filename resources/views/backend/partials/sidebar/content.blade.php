{{-- Content Management Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Manage Content</span>
</li>

<!-- Faq-->
@can('view faq')
    <li class="menu-item {{ request()->is('admin/faqs*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-help"></i>
            <div data-i18n="Faq">Faq</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/faqs*') ? 'active' : '' }}">
                <a href="{{ route('admin.faqs.index') }}" class="menu-link">
                    <div data-i18n="Faqs">Faqs</div>
                </a>
            </li>
            <li class="menu-item  {{ request()->is('admin/faqs/create') ? 'active' : '' }}">
                <a href="{{ route('admin.faqs.create') }}" class="menu-link">
                    <div data-i18n="Add Faq">Add Faq</div>
                </a>
            </li>
        </ul>
    </li>
@endcan

<!--Event Categories-->
@can('view event_category')
    <li class="menu-item  {{ request()->is('admin/event_categories*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-category"></i>
            <div data-i18n="Category">Category</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/event_categories*') ? 'active' : '' }}">
                <a href="{{ route('admin.event_categories.index') }}" class="menu-link">
                    <div data-i18n=" Categories">Categories</div>
                </a>
            </li>
        </ul>
    </li>
@endcan
