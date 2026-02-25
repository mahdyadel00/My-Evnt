<footer class="footer-section">
    <div class="container">
      <div class="footer-content pt-5 pb-3">
        <div class="row">
          <div class="col-xl-4 col-lg-4 mb-50">
            <div class="footer-widget">
              <div class="footer-logo">
                  <a href="{{ route('home') }}" style="font-size: 30px;font-weight: 700; color:#fff">
                      @foreach($setting->media as $media)
                          @if($media->name == 'footer_logo')
                            <img src="{{ asset('storage/'.$media->path) }}" alt="logo">
                          @endif
                      @endforeach
                  </a>
              </div>
              <div class="footer-text">
                <p>{!! $setting->description !!}</p>
              </div>
              <div class="footer-social-icon">
                <span>Follow us</span>
                <a href="{{ $setting->facebook }}"><i class="fab fa-facebook-f facebook-bg"></i></a>
                <a href="{{ $setting->twitter }}"><i class="fab fa-twitter twitter-bg"></i></a>
                <a href="{{ $setting->instagram }}"><i class="fab fa-instagram instagram-bg"></i></a>
                @if(!empty($setting->whatsapp))
                    <a href="{{ $setting->whatsapp }}"><i class="fab fa-whatsapp whatsapp-bg"></i></a>
                @endif
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <div class="footer-widget-heading">
                <h3>Event Types</h3>
              </div>
              <ul>
                  @foreach($most_popular_event_category as $footer_category)
                    <li><a href="{{ route('events_category' , $footer_category) }}"> {{ $footer_category->name }}</a></li>
                  @endforeach
              </ul>
            </div>
          </div>
          <div class="col-xl-2 col-lg-4 col-md-6 mb-30">
            <div class="footer-widget">
              <div class="footer-widget-heading">
                <h3>Useful Links</h3>
              </div>
              <ul>
                  @if(Auth::check())
                    <li><a href="{{ route('profile') }}">My Profile</a></li>
                  @endif
                <li><a href="{{ route('login') }}">My Profile</a></li>
                <li><a href="{{ route('faq') }}">FAQS</a></li>
<!--                <li><a href="{{ route('blogs') }}">Blog</a></li>-->
                <li><a href="{{ route('contacts') }}">Contact us</a></li>
              </ul>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
            <div class="footer-widget">
              <div class="footer-widget-heading">
                <h3>Subscribe</h3>
              </div>
              <div class="footer-text mb-25">
                <p>Don’t miss to subscribe to our new feeds, kindly fill the form below.</p>
              </div>
              <div class="subscribe-form">
                  <form method="post" id="subscribe-forme">
                    @csrf
                    <input type="email" name="email" placeholder="Email">
                        <button id="subscribe-btn"><i class="fab fa-telegram-plane"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright-area">
      <div class="container">
        <div class="row">
          <div class="col-xl-6 col-lg-6 text-center text-lg-left">
            <div class="copyright-text">
              <p>Copyright &copy; 2025, All Right Reserved <a href="{{ route('home') }}">MyEvnt</a></p>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 text-center text-lg-left">
            <div class="footer-menu">
              <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">Privacy</a></li>
                <li><a href="#">Policy</a></li>
                <li><a href="{{ route('contacts') }}">Contact</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

@push('js')
    <script>
        $('#subscribe-forme').submit(function (e) {
            e.preventDefault();
            var email = $('#subscribe-forme input[name="email"]').val();
            $.ajax({
                url: '{{ route('subscribe') }}',
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.message == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Subscribed Successfully',
                        });
                        $('#subscribe-forme').trigger('reset');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                }
            });
        });
    </script>
@endpush



