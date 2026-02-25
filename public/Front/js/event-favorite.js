/**
 * =====================================================
 * Event Favorite/Unfavorite Handler
 * =====================================================
 *
 * Reusable JavaScript module for handling event favorites
 * across multiple pages with live updates.
 *
 * Usage:
 * 1. Include this file in your blade template
 * 2. Make sure your heart icon has class "heart-icon" and data-event-id
 * 3. The script will handle the rest automatically
 *
 * Example HTML:
 * <a href="#" class="heart-icon" data-event-id="123">
 *     <i class="fa-regular fa-heart"></i>
 * </a>
 */

(function($) {
    'use strict';

    // Configuration
    const CONFIG = {
        favoriteUrl: '/favourite',
        csrfToken: $('meta[name="csrf-token"]').attr('content'),
        iconSelector: '.heart-icon',
        heartIconClass: 'fa-heart',
        solidClass: 'fa-solid',
        regularClass: 'fa-regular'
    };

    /**
     * Initialize the favorite handler
     */
    function init() {
        // Use Event Delegation for dynamically loaded elements
        $(document).on('click', CONFIG.iconSelector, handleFavoriteClick);
    }

    /**
     * Handle favorite/unfavorite click event
     * @param {Event} e - Click event
     */
    function handleFavoriteClick(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling

        const $icon = $(this);
        const eventId = $icon.data('event-id');

        // Validate event ID
        if (!eventId) {
            console.error('Event ID is missing');
            return;
        }

        // Check if user is authenticated
        if (!CONFIG.csrfToken) {
            showNotification('error', 'Please login first!');
            return;
        }

        // Prevent multiple clicks
        if ($icon.hasClass('processing')) {
            return;
        }

        // Mark as processing
        $icon.addClass('processing');

        // Send AJAX request
        toggleFavorite(eventId, $icon);
    }

    /**
     * Toggle favorite status
     * @param {number} eventId - Event ID
     * @param {jQuery} $icon - Heart icon element
     */
    function toggleFavorite(eventId, $icon) {
        $.ajax({
            url: CONFIG.favoriteUrl,
            type: 'POST',
            data: {
                event_id: eventId,
                _token: CONFIG.csrfToken
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update icon state
                    updateIconState($icon, response.message, eventId);

                    // Show success notification
                    showNotification('success', response.message);
                } else {
                    // Handle error
                    showNotification('error', response.message || 'Something went wrong!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Favorite error:', error);

                // Handle different error types
                if (xhr.status === 401) {
                    showNotification('error', 'Please login first!');
                } else if (xhr.status === 404) {
                    showNotification('error', 'Event not found!');
                } else {
                    showNotification('error', 'Something went wrong. Please try again!');
                }
            },
            complete: function() {
                // Remove processing state
                $icon.removeClass('processing');
            }
        });
    }

    /**
     * Update icon visual state (Backend only - no CSS modifications)
     * @param {jQuery} $icon - Heart icon element
     * @param {string} message - Response message
     * @param {number} eventId - Event ID
     */
    function updateIconState($icon, message, eventId) {
        const $heartIcon = $icon.find('i');
        const isFavorited = message.includes('added');

        // Toggle icon classes ONLY (no animations that affect design)
        if (isFavorited) {
            // Change to solid (favorited)
            $heartIcon.removeClass(CONFIG.regularClass).addClass(CONFIG.solidClass);
            $icon.addClass('active');
        } else {
            // Change to regular (unfavorited)
            $heartIcon.removeClass(CONFIG.solidClass).addClass(CONFIG.regularClass);
            $icon.removeClass('active');
        }

        // Update all instances of this event's favorite icon on the page
        updateAllEventIcons(eventId, isFavorited);
    }

    /**
     * Update all favorite icons for the same event across the page
     * @param {number} eventId - Event ID
     * @param {boolean} isFavorited - Is event favorited
     */
    function updateAllEventIcons(eventId, isFavorited) {
        $(CONFIG.iconSelector + '[data-event-id="' + eventId + '"]').each(function() {
            const $heartIcon = $(this).find('i');

            if (isFavorited) {
                $heartIcon.removeClass(CONFIG.regularClass).addClass(CONFIG.solidClass);
                $(this).addClass('active');
            } else {
                $heartIcon.removeClass(CONFIG.solidClass).addClass(CONFIG.regularClass);
                $(this).removeClass('active');
            }
        });
    }

    /**
     * Show notification using SweetAlert2
     * @param {string} type - Notification type (success, error, warning, info)
     * @param {string} message - Notification message
     */
    function showNotification(type, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Success!' : 'Oops...',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else {
            // Fallback to alert if SweetAlert2 is not available
            alert(message);
        }
    }

    /**
     * Get event ID from icon element (helper function)
     * @param {jQuery} $icon - Icon element
     * @returns {number|null} Event ID
     */
    function getEventId($icon) {
        const eventId = $icon.data('event-id');
        return eventId ? parseInt(eventId) : null;
    }

    // Initialize on document ready
    $(document).ready(function() {
        init();
        console.log('Event Favorite Handler Initialized ✓');
    });

    // Expose public API (optional)
    window.EventFavorite = {
        init: init,
        toggle: function(eventId) {
            const $icon = $(CONFIG.iconSelector + '[data-event-id="' + eventId + '"]').first();
            if ($icon.length) {
                toggleFavorite(eventId, $icon);
            }
        }
    };

})(jQuery);

