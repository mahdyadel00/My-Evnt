@extends('Frontend.layouts.master')
@section('title' , 'Contact us')
@section('content')
    <!-- start home section -->
    <section class="section">
        <div class="container pages_container">
            <div class="col-lg-7 pages_data mt-3">
                <h1 class="pages_title"><span>Contact Us </span></h1>
                <p class="pages_description pages-paragraph">If you encounter any difficulties while using our ticket selling platform, you can always count on our assistance. My-Event Team is here to answer all of your questions.</p>
            </div>
            <div class="col-lg-5 pages_image">
                <img src="{{ asset('Front') }}/img/contact-us-img.svg" alt="home">
            </div>
        </div>
    </section>
    <!-- end home section -->
    <!-- start section contact us  -->
    <section class="container-contact">
        <main class="row-contact">
            <section class="col-contact left-contact">
                <div class="contactTitle">
                    <h2>Contact Us</h2>
                    <p>If you have any work from me or any types of quries related to my tutorial, you can send me message from here. It's my pleasure to help you.</p>
                </div>
                <div class="contactInfo">
                    <div class="iconGroup">
                        <div class="icon"> <i class="fa-brands fa-square-whatsapp"></i></div>
                        <div class="details">
                            <span><a style="color: #222;" href="tel:{{ $setting->phone }}">Phone</a></span>
                            <span><a style="color: #878787;" href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a></span>
                        </div>
                    </div>
                    <div class="iconGroup">
                        <div class="icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="details">
                            <span><a style="color: #222;" href="mailto:{{ $setting->email }}">Email</a></span>
                            <span><a style="color: #878787;" href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></span>
                        </div>
                    </div>
                    <div class="iconGroup">
                        <div class="footer-social-icon">
                            <h3>Follow Us</h3>
                            <a href="{{ $setting->facebook }}" {{ $setting->facebook == null ? 'hidden' : '' }}><i class="fa-brands fa-facebook facebook-bg"></i></a>
                            <a href="{{ $setting->instagram }}" {{ $setting->instagram == null ? 'hidden' : '' }}><i class="fa-brands fa-instagram instagram-bg"></i></a>
                            <a href="{{ $setting->whats_app }}" {{ $setting->whats_app == null ? 'hidden' : '' }}><i class="fa-brands fa-whatsapp whatsapp-bg"></i></a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-contact right-contact">
                <form class="messageForm" id="contactForm" method="post">
                    @csrf
                    <div class="inputGroup halfWidth">
                        <input type="email" name="email" required="required" placeholder="Email Address">
                        <label>Email</label>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="inputGroup halfWidth">
                        <input type="text" name="phone" required="required" placeholder="Phone Number">
                        <label>Phone</label>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="inputGroup fullWidth">
                        <input type="text" name="subject" required="required" placeholder="Subject">
                        <label>Subject</label>
                        @error('subject')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="inputGroup fullWidth">
                        <textarea required="required" name="message" placeholder="Message"></textarea>
                        <label>Message</label>
                        @error('message')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="inputGroup fullWidth">
                        <button id="submit" type="submit" class="disabled">Send Message</button>
                    </div>
                </form>
            </section>
        </main>
    </section>
    <!-- end section contact us  -->
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        const $form = $('#contactForm');
        const $inputs = $form.find('input[required], textarea[required]');
        const $submitBtn = $('#submit');

        function checkForm() {
            let allFilled = true;

            $inputs.each(function () {
                if (!$(this).val().trim()) {
                    allFilled = false;
                }
            });

            if (allFilled) {
                $submitBtn.removeClass('disabled').prop('disabled', false);
            } else {
                $submitBtn.addClass('disabled').prop('disabled', true);
            }
        }

        $inputs.on('input change', checkForm);

        checkForm();

        $form.on('submit', function (e) {
            e.preventDefault();
            let formData = $form.serialize();

            $.ajax({
                url: "{{ route('contacts.store') }}",
                type: "POST",
                data: formData,
                success: function (data) {
                    if (data.message === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Message Sent Successfully',
                        });
                        $form.trigger('reset');
                        checkForm();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Message Not Sent',
                        });
                    }
                }
            });
        });
    });
</script>
@endpush

