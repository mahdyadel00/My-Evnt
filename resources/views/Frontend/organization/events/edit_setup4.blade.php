@extends('Frontend.organization.events.inc.master')
@section('title', 'Edit Event Setup 4')
@section('content')
    <!-- start breadcrumb  -->
    <div class="head-title" style="margin-bottom: 20px; ">
        <div class="left">
            <ul class="breadcrumb" style="margin-bottom: 20px;">
                <li>
                    <a class="active" href="{{ route('home') }}">Home</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="#">Edit Event</a>
                </li>
            </ul>
            <h1>Edit Event</h1>
        </div>
    </div>
    <!-- end breadcrumb  -->
    @include('Frontend.organization.layouts._message')
    <!-- start form-data -->
    <div class="form-data">
        <div class="head">
            <h3> Tickets Info </h3>
            <p>Step 4 of 5</p>
        </div>
        <!-- form step 2 -->
        <div class="row">
            <div class="col-12">
                <div class="card-header">
                    <div class="p-2">
                        <!-- Credit card form tabs -->
                        <ul class="nav nav-pills rounded nav-fill mb-3" role="tablist">
                            <li class="nav-item d-flex justify-content-center align-items-center {{ $event->paymentMethods->where('user_id', auth()->id())->first()->type == 'pay-credit_card' ? 'active' : '' }}">
                                <a style="width: 180px;" class="nav-link active p-2" data-bs-toggle="pill" href="#pay-online"
                                   role="tab">
                                    Pay Online
                                </a>
                            </li>
                            <li class="nav-item d-flex justify-content-center align-items-center {{ $event->paymentMethods->where('user_id', auth()->id())->first()->type == 'cache' ? 'active' : '' }}">
                                <a style="width: 180px;" class="nav-link p-2" data-bs-toggle="pill" href="#office" role="tab">
                                    Ticket Office
                                </a>
                            </li>
                            <li class="nav-item d-flex justify-content-center align-items-center {{ $event->paymentMethods->where('user_id', auth()->id())->first()->type == 'transfer_bank' ? 'active' : '' }}">
                                <a style="width: 180px;" class="nav-link" id="net-banking-tab" data-bs-toggle="pill"
                                   href="#net-banking" role="tab">
                                    Bank
                                </a>
                            </li>
                        </ul>
                    </div> <!-- End Credit card form tabs -->
                </div>
                <!-- Credit card form content -->
                <div class="card-body tab-content">
                    <!-- pay online -->
                    <div id="pay-online" class="tab-pane fade show active pt-3">
                        <form class="row g-3" method="post" action="{{ route('organization.update_setup4' , $event->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="radio" name="type" value="pay-credit_card" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Card Owner </label>
                                <input type="text" class="form-control p-2" placeholder="Card Owner Name" name="card_name"  value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->card_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Card Number </label>
                                <input type="text" class="form-control p-2" placeholder="Card Owner Number " maxlength="15" name="card_number" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->card_number }}">
                            </div>
                            <div class="col-md-6">
                                <label for="expiryDate" class="form-label">
                                    Expiration Date
                                </label>
                                <div class="input-group">
                                    <input type="text" id="expiryDate" class="form-control" placeholder="MM/YY" maxlength="5" name="card_expiry" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->card_expiry }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control p-2" placeholder="CVC " maxlength="3" name="card_cvc" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->card_cvc }}">
                            </div>
                            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                                <a href="{{ route('create_event_setup3') }}" type="submit" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">Back</a>
                                <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Next</button>
                            </div>
                        </form>
                    </div>
                    <!-- End credit card info -->
                    <!-- office info -->
                    <div id="office" class="tab-pane fade pt-3">
                        <form class="row g-3" method="post" action="{{ route('organization.edit_cache' , $event->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="radio" name="type" value="cache" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Where to Buy Tickets
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Address of the ticket selling point" name="address" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->address }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Merchant Contacts
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Email " name="email" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Number Phone " name="phone" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->phone }}">
                            </div>
                            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                                <a href="{{ route('create_event_setup3') }}" type="submit" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">Back</a>
                                <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Next</button>
                            </div>
                        </form>
                    </div>
                    <!-- End Paypal info -->
                    <!-- bank info -->
                    <div id="net-banking" class="tab-pane fade pt-3">
                        <form class="row g-3" method="post" action="{{ route('organization.edit_transfer_bank' , $event->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <label class="form-label">Bank Name
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Bank Name " name="bank_name" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->bank_name }}">
                                @error('bank_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Email" name="email" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->email }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Number Phone " name="phone" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->phone }}">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Branch
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Branch" name="branch" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->branch }}">
                                @error('branch')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Postal Code
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Postal Code " name="postal_code" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->postal_code }}">
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IBAN
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter IBAN " name="iban" value="{{ $event->paymentMethods->where('user_id', auth()->id())->first()->iban }}">
                                @error('iban')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <select class="form-select p-2" name="country_id">
                                    <option disabled selected>Choose Country</option>
                                   @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $event->paymentMethods->where('user_id', auth()->id())->first()->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <select class="form-select p-2" name="city_id">
                                    <option disabled selected>Choose City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ $event->paymentMethods->where('user_id', auth()->id())->first()->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Holder Name
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Account Name " name="account_name">
                                @error('account_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Number
                                </label>
                                <input type="text" class="form-control p-2" placeholder="Enter Account Number " name="account_number">
                                @error('account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                                <a href="{{ route('create_event_setup3') }}" type="submit" class="btn btn-secondary" style="padding: 7px 30px;margin:0 10px">Back</a>
                                <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Next</button>
                            </div>
                        </form>
                    </div>
                    <!-- bank info -->
                </div> <!-- End card body -->
            </div> <!-- End col-lg-6 -->
        </div> <!-- End row -->
    </div>
    <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
    //get city by country
    $(document).ready(function () {
        $('select[name="country_id"]').on('change', function () {
            var country_id = $(this).val();
            if (country_id) {
                $.ajax({
                    url: '{{ route('get_cities') }}/',
                    data: {country_id: country_id},
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('select[name="city_id"]').empty();
                        $.each(data, function (key, value) {
                            //check if value is equal to the selected value
                            $('select[name="city_id"]').append('<option disabled selected>Select City</option>');
                            $('select[name="city_id"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="city_id"]').empty();
            }
        });
    });
</script>
@endpush
