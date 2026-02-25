<!-- Newsletter Subscription Section -->
<section class="newsletter-section-new" id="subscribe">
    <div class="newsletter-container-new">
        <div class="newsletter-content-new">
            <div class="newsletter-text-new">
                <h2 class="newsletter-title-new">
                    Subscribe to our newsletter!
                </h2>
                <p class="newsletter-description-new">
                    Stay updated with the latest events, exclusive
                    offers, and early bird discounts. Join thousands of
                    event enthusiasts who never miss out!
                </p>
                <div class="newsletter-features-new">
                    <div class="newsletter-feature-new">
                    <i class="fa-thin fa-calendar"></i>
                        <span>Latest Events</span>
                    </div>
                    <div class="newsletter-feature-new">
                    <i class="fa-thin fa-tag"></i>
                        <span>Exclusive Discounts</span>
                    </div>
                    <div class="newsletter-feature-new">
                    <i class="fa-thin fa-bell"></i>
                        <span>Early Access</span>
                    </div>
                </div>
            </div>
            <div class="newsletter-form-container-new">
                <form class="newsletter-form-new" id="subscribe-form" method="post">
                    @csrf
                    <div class="newsletter-input-group-new">
                        <div class="newsletter-input-wrapper-new">
                            <i class="fas fa-envelope newsletter-input-icon-new"></i>
                            <input type="email" class="newsletter-input-new" placeholder="Enter your email address"
                                required id="subscribe-email" name="email" />
                        </div>
                        <button type="submit" class="newsletter-btn-new" id="subscribe-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                            <span class="newsletter-btn-text">Subscribe</span>
                            <i class="fas fa-paper-plane newsletter-btn-icon"></i>
                        </button>
                    </div>
                    <div class="newsletter-privacy-new">
                        <i class="fas fa-lock"></i>
                        <span>We respect your privacy. Unsubscribe at any
                            time.</span>
                    </div>
                </form>
            </div>
        </div>
        <div class="newsletter-decoration-new">
            <div class="newsletter-circle newsletter-circle-1-new"></div>
            <div class="newsletter-circle newsletter-circle-2-new"></div>
            <div class="newsletter-circle newsletter-circle-3-new"></div>
        </div>
    </div>
</section>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('subscribe-email');
        const subscribeBtn = document.getElementById('subscribe-btn');
        
        // Email validation regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        // Function to toggle button state
        function toggleButtonState() {
            const email = emailInput.value.trim();
            const isValid = email.length > 0 && emailRegex.test(email);
            
            if (isValid) {
                subscribeBtn.disabled = false;
                subscribeBtn.style.opacity = '1';
                subscribeBtn.style.cursor = 'pointer';
            } else {
                subscribeBtn.disabled = true;
                subscribeBtn.style.opacity = '0.6';
                subscribeBtn.style.cursor = 'not-allowed';
            }
        }
        
        // Listen to input events
        emailInput.addEventListener('input', toggleButtonState);
        emailInput.addEventListener('blur', toggleButtonState);
        
        // Initial check
        toggleButtonState();
    });
</script>
@endpush