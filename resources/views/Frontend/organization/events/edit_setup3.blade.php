@extends('Frontend.organization.events.inc.master')
@section('title', 'Edit Event Setup 3')
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
                <p>Step 3 of 5</p>
            </div>
            <!-- form step 2 -->
            <form class="row g-3" action="{{ route('organization.update_setup3' , $event) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row pt-5" id="new-ticket">
                <div class="col-12">
                    <p class="notice-info-place">One ticket type for universal event access. Multiple ticket types for varied
                        opportunities based on price.
                    </p>
                </div>
                @foreach($event->tickets as $ticket)
                    <div class="col-md-6" id="ticket_type">
                        <label class="form-label">Ticket Type</label>
                        <input class="form-control p-2" type="text" placeholder="Ticket Type" name="ticket_type[]" value="{{ $ticket->ticket_type }}">
                        @error('ticket_type')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6" id="price">
                        <label class="form-label"> price</label>
                        <input class="form-control p-2" type="number" placeholder="price of ticket" name="price[]" value="{{ $ticket->price }}">
                        @error('price')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6" id="quantity">
                        <label class="form-label"> Quantity</label>
                        <input class="form-control p-2" type="number" placeholder="Quantity" name="quantity[]" value="{{ $ticket->quantity }}">
                        @error('quantity')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 mt-4">
                        <a id="remove_ticket" class="btn btn-outline-danger" style="padding: 7px 30px;">Delete Ticket Type</a>
                    </div>
                @endforeach
                    <div class="col-12">
                        <a id="add_ticket" class="btn btn-outline-website" style="padding: 7px 30px;">Add Ticket Type</a>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center mt-5 mb-3">
                    <a href="{{ route('create_event_setup2') }}" type="submit" class="btn btn-secondary"
                       style="padding: 7px 30px;margin:0 10px">Back</a>
                    <button type="submit" class="btn btn-primary" style="padding: 7px 30px;margin:0 10px">Next</button>
                </div>
            </form>
        </div>
        <!-- end form-data -->
@endsection
@push('inc_events_js')
    <script>
        //when click on add ticket type
        $('#add_ticket').click(function () {
        $('#new-ticket').append(
            '<div class="col-md-6">\n' +
            '<label class="form-label mt-3">Ticket Type</label>\n' +
            '<input class="form-control p-2" type="text" placeholder="Ticket Type" name="ticket_type[]">\n' +
            '@error('ticket_type')\n' +
        '<p class="text-danger">{{ $message }}</p>\n' +
        '@enderror\n' +
        '</div>\n' +
        '<div class="col-md-6" id="price">\n' +
        '<label class="form-label mt-3"> price</label>\n' +
        '<input class="form-control p-2" type="number" placeholder="price of ticket" name="price[]">\n' +
        '@error('price')\n' +
        '<p class="text-danger">{{ $message }}</p>\n' +
        '@enderror\n' +
        '</div>\n' +
        '<div class="col-md-6" id="quantity">\n' +
        '<label class="form-label mt-3"> Quantity</label>\n' +
        '<input class="form-control p-2" type="number" placeholder="Quantity" name="quantity[]">\n' +
        '@error('quantity')\n' +
        '<p class="text-danger">{{ $message }}</p>\n' +
        '@enderror\n' +
        '</div>\n' +
        '<div class="col-12 mt-4">\n' +
        '<a id="remove_ticket" class="btn btn-outline-danger" style="padding: 7px 30px;">Remove Ticket Type</a>\n' +
        '</div>'
        );
    });
        //when click on remove ticket type
        $(document).on('click', '#remove_ticket', function () {
        $(this).parent().prev().remove();
        $(this).parent().prev().remove();
        $(this).parent().prev().remove();
        $(this).parent().prev().remove();
        $(this).parent().remove();
    });
    </script>
@endpush
