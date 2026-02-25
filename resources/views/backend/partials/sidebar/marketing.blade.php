{{-- Marketing & Media Section --}}
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Marketing & Media</span>
</li>

<!--Our Partners-->
@can('view partners')
    <li class="menu-item {{ request()->is('admin/partners*') ? 'active' : '' }}">
        <a href="{{ route('admin.partners.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-users"></i>
            <div data-i18n="Our Partners">Our Partners</div>
        </a>
    </li>
@endcan

<!--Social Gallery-->
@can('view social_gallery')
    <li class="menu-item {{ request()->is('admin/social-galleries*') ? 'active' : '' }}">
        <a href="{{ route('admin.social_galleries.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-photo"></i>
            <div data-i18n="Social Gallery">Social Gallery</div>
        </a>
    </li>
@endcan

<!-- Blog Management -->
@can('view blog')
    <li class="menu-item {{ request()->is('admin/blogs*') ? 'active' : '' }}">
        <a href="{{ route('admin.blogs.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-news"></i>
            <div data-i18n="Blog">Blog</div>
        </a>
    </li>
@endcan

<!-- Article Management -->
@can('view article')
    <li class="menu-item {{ request()->is('admin/articles*') ? 'active' : '' }}">
        <a href="{{ route('admin.articles.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-article"></i>
            <div data-i18n="Article">Article</div>
        </a>
    </li>
@endcan

<!-- Coming Soon -->
<!--AdFee-->
<!-- @can('view ad_fee')
    <li class="menu-item {{ request()->is('admin/ad_fees*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-credit-card"></i>
            <div data-i18n="Ad Fee">Ad Fee</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/ad_fees*') ? 'active' : '' }}">
                <a href="{{ route('admin.ad_fees.index') }}" class="menu-link">
                    <div data-i18n="Ad Fees">Ad Fees</div>
                </a>
            </li>
            <li class="menu-item  {{ request()->is('admin/ad_fees/create') ? 'active' : '' }}">
                <a href="{{ route('admin.ad_fees.create') }}" class="menu-link">
                    <div data-i18n="Add Ad Fee">Add Ad Fee</div>
                </a>
            </li>
        </ul>
    </li>
@endcan -->
