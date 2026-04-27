@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\HomePopupFeature> $home_popup_features */
    $popup = $home_popup_features->first();
@endphp
@if ($popup)
    <!-- POPUP OVERLAY BACKDROP -->
    <div class="popup-overlay-backdrop" id="jsPopupBackdrop" role="dialog" aria-modal="true" aria-labelledby="jsPopupTitle">
        <button id="jsOpenModal" type="button" style="display: none"></button>
        <div class="popup-container-wrapper popup-container-wrapper--home-feature">
            <div class="popup-image-banner-area">
                <a href="{{ $popup->resolveBannerLinkUrl() }}" class="banner-photo-link">
                    <img class="banner-photo-img" src="{{ $popup->resolveImageUrl() }}"
                        alt="{{ e($popup->resolveTitleDisplay()) }}" width="800" height="500" loading="lazy" />
                </a>

                <div class="popup-banner-topbar">
                    <div class="banner-type-label">{{ e($popup->resolveBadgeDisplay()) }}</div>

                    <button class="banner-x-close-btn" id="jsCloseBanner" type="button" aria-label="Close popup">
                        ✕
                    </button>
                </div>
            </div>

            <div class="popup-body-content-area">
                <div class="body-title-rating-row">
                    <h2 class="body-main-title-text" id="jsPopupTitle">
                        {{ e($popup->resolveTitleDisplay()) }}
                    </h2>
                </div>

                @php
                    $descPreview = $popup->resolveDescriptionPreview(200);
                @endphp
                @if (filled($descPreview))
                    <p class="body-description-paragraph">{{ e($descPreview) }}</p>
                @endif

                <div class="popup-meta-row" aria-label="Event details">
                    @if ($popup->resolveLocationDisplay() !== '')
                        <div class="popup-meta-item">
                            <i class="fas fa-location-dot" aria-hidden="true"></i>
                            <span
                                title="{{ e($popup->resolveLocationDisplay()) }}">{{ e($popup->resolveLocationDisplay()) }}</span>
                        </div>
                    @endif
                    @if ($popup->resolveDatetimeDisplay() !== '')
                        <div class="popup-meta-item">
                            <i class="fas fa-calendar-days" aria-hidden="true"></i>
                            <span>{{ e($popup->resolveDatetimeDisplay()) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if ($popup->show_action_buttons ?? true)
                <div class="popup-footer-actions-bar">
                    <button class="footer-ghost-dismiss-btn" id="jsDismissBtn" type="button">
                        {{ e($popup->resolveDismissLabelDisplay()) }}
                    </button>
                    <a class="footer-primary-cta-btn" id="jsConfirmBtn" href="{{ $popup->resolveCtaUrl() }}">
                        {{ e($popup->resolveCtaLabelDisplay()) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif