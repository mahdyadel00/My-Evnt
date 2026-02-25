<section class="details">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-7">
                @forelse($event->media as $media)
                @if($media->name == 'banner')
                <img src="{{ asset('storage/'.$media->path) }}" alt="project-image" class="rounded img-details">
                @endif
                @empty
                <img src="{{ asset('img/card1.png') }}" alt="project-image" class="rounded img-details">
                @endforelse
                <div class="project-info-box">
                    <h5 class="mb-3"><b>{{ $event->name }}</b></h5>
                    <p class="mb-0 ">{!! $event->description !!}</p>
                    <p class="mb-0"><span style="font-weight: 700; padding: 0 5px;">Hosted By : </span>
                        <a style="color:#ed7226c0" href="{{ $event->company ? route('company_profile', $event->company->id) : '#' }}">{{ $event->company?->company_name }}</a>
                    </p>
                </div>
                <div class="container container-details mt-5">
                    <!-- <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #fff;"> -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tickets-tab" data-toggle="tab" href="#tickets" role="tab"
                               aria-controls="tickets" aria-selected="true">Tickets</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tickets" role="tabpanel" aria-labelledby="tickets-tab" style="padding: 0">
                            <!-- start Tickets content  -->
                            @foreach($event->tickets as $ticket)
                            <div class="card-body card-bottom mobile-card-body" style="padding: 30px 5px">
                                <h5 class="card-title">{{ $ticket->ticket_type }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Description</h6>
                                <p class="card-text"> All types of tickets details are available here . </p>
                                <div class="d-flex justify-content-between align-items-baseline flex-wrap">
                                    <div class="col-md-4 mb-2 mt-2">
                                            <span class="p-2 rounded span-data" style="background-color:rgb(240, 240, 240)"> {{ $ticket->price }} {{ $event->currency?->code }}
                                            </span>
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <span class="p-2 rounded span-data" style="background-color:rgb(240,240,240)">Available :<span> {{ $ticket->quantity }}</span></span>
                                    </div>
                                    <div class="col-md-4 p-2">
                                            <span class="p-2 rounded span-data" style="background-color:rgb(240,240,240)">Sold Out :<span>
                                                <!-- calculate sold out tickets -->
                                                @php
                                                    $sold_out = App\Models\Order::where('ticket_id' , $ticket->id)->where('payment_status' , 'paid')->sum('quantity');
                                                    echo $sold_out;
                                                @endphp
                                                </span>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <!-- end Tickets content-->
                        </div>
                        {{--                            <!-- start other dates -->--}}
                        {{--                            <div class="tab-pane fade p-4" id="other-dates" role="tabpanel" aria-labelledby="other-dates-tab">--}}
                            {{--                                <!-- start dates content -->--}}
                            {{--                                <div class="text-center mt-3">--}}
                                {{--                                    <h5>Other performances by this artist are not available.</h5>--}}
                                {{--                                    <p>Don't miss the opportunity to visit this concert.</p>--}}
                                {{--                                    <button class="btn btn-success">Buy Tickets</button>--}}
                                {{--                                </div>--}}
                            {{--                                <!-- end dates content -->--}}
                            {{--                            </div>--}}
                        {{--                            <!-- end other dates -->--}}
                    </div>
                </div>
            </div>
            <!-- start part get ticket -->
            <div class="col-md-5">
                <div class="project-info-box mt-0">
                    <h4>{{ $event->name }}</h4>
                    <div class="mt-4">
                        <p> <span class="p-2"><i class="fa-solid fa-calendar-days"></i></span> {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</p>
                        <p> <span class="p-2"><i class="fa-solid fa-clock"></i></span>  {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</p>
                        <div>
                            <p> <span class="p-2"><i class="fa-solid fa-location-dot"></i></span>
                                {{-- go to location by embed map --}}
                                <a href="https://www.google.com/maps/search/{{ $event->location }}" target="_blank">{{ $event->city->name }}</a>
                            </p>
                        </div>
                        <p class="pt-3"> <span class="p-2"><i class="fa-solid fa-tag"></i></span> Normal Price :    {{ $event->tickets->isNotEmpty() ? $event->tickets->first()->price : 0 }}{{ $event->currency?->code }}</p>
                        <!--get all tickets -->
                        <div class="d-flex justify-content-center align-items-baseline">
                            <!--when check box is checked store in session -->
                            <a href="{{  route('checkout_user' , $event->id) }}" class="btn-ticket mr-2 m-2">Get Tickets</a>
                            @php
                            if(auth()->check()){
                            $event->is_favourite = auth()->user()->favourites->pluck('event_id')->toArray();
                            }
                            @endphp
                            @if(auth()->check())
                            <a href="#" class="btn-outline m-2 heart" id="heart-icon" style="padding: 12px 15px ;" data-event-uuid="{{ $event->uuid }}">
                                <i class="fa-{{ in_array($event->id , $event->is_favourite) ? 'solid' : 'regular' }} fa-heart"></i>
                            </a>
                            @endif
                        </div>
                        <!-- icon for share link by social media -->
                        <div class="share-links">
                            <p><b>Share with friends</b></p>
                            <div class="social-icons links">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('event' , $event->uuid) }}" target="_blank">
                                    <i class="fa-brands fa-facebook"></i>
                                </a>
                                <a href="https://wa.me/?text={{ route('event' , $event->uuid) }}" target="_blank">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ route('event' , $event->uuid) }}" target="_blank">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                <a href="https://www.instagram.com/" target="_blank">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('event' , $event->uuid) }}" target="_blank">
                                    <i class="fa-brands fa-linkedin"></i>
                                </a>

                                <!-- share by email , instagram  , copy link -->
                                <!-- <a href="mailto:?subject=Check this event&body={{ route('event' , $event->uuid) }}" target="_blank">
                                    <i class="fa-regular fa-envelope"></i>
                                </a> -->

                                <a href="#" id="copy" data-clipboard-text="{{ route('event' , $event->uuid) }}">
                                    <i class="fa-regular fa-copy"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cancellation policy -->
                <div class="project-info-box mt-0">
                    <h4>Cancellation Policy</h4>
                    <div class="mt-2">
                        <div>
                            <p class="p-2">{!! substr( Str::limit($event->cancellation_policy , 80) , 0 , 80) !!}</p>
                            <a href="#" class="btn btn-outline-dark" data-bs-target="#cancellation_policy" data-bs-toggle="modal">Show More</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- popup to complete cancellation policy -->
            <div class="modal" id="cancellation_policy" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Cancellation Policy</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>{!! $event->cancellation_policy !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end part get ticket -->
        </div>
        <!-- end part get ticket -->
    </div>
</section>
