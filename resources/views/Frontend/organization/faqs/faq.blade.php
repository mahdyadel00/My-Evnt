@extends('Frontend.organization.layouts.master')
@section('title', 'FAQs')
@section('organization')
<!-- start home section -->
<section class="section ">
    <div class="container pages_container">
        <div class="col-lg-7 pages_data mt-3">
            <h1 class="pages_title"><span>Frequently Asked Questions</span></h1>
            <p class="pages_description pages-paragraph">If you encounter any difficulties while using our ticket selling
                platform, you can always count on our assistance. Event Team is here to answer all of your questions.
            </p>

        </div>
        <div class="col-lg-5 pages_image">
            <img style="height: 250px;" src="{{ asset('Front') }}/img/faqs.svg" alt="home">
        </div>
    </div>
</section>
<!-- end home section -->

<!-- Start FAQ  -->
<section class="faqs-section mt-5 mb-5">
    <h4 style="font-weight: 600;" class="text-center">Frequently Asked Questions</h4>
    <div class="accordion-section">
        @foreach($faqs as $faq)
            <div class="accordion-item mb-4 mt-4">
            <div class="accordion-item-header">
                <h3>{{ $faq->question }}</h3>
            </div>
            <div class="accordion-item-body">
                <div class="accordion-item-body-content">
                    <p>{!! $faq->answer !!}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
<!-- End FAQ  -->

<!-- start Ready to Try the Platform? -->
<section class="section-how-work">
    <div class="container">
        <div class="row justify-content-center text-center ">
            <div class="col-md-10 col-lg-8">
                <div class="header-section">
                    <h2 class="title">For Further assistance <span>assistance</span> </h2>
                    <p class="description">If you would like to contact us regarding a particular event or order, please log in
                        and leave your message in the support chat. Our support team will address any issue and provide all the
                        help you need.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center m-4">
        <a href="{{ route('organization_login') }}" class="button-event">Log In</a>
    </div>
</section>
<!-- end Ready to Try the Platform? -->
@endsection
