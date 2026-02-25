@extends('backend.partials.master')

@section('title', 'Manage Webinar Cards')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Webinar Cards</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Cities List</h5> -->
                @can('create webinar_cards')
                    <a href="{{ route('admin.webinar_cards.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Webinar Card
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Webinar</th>
                            <th style="text-align: center">Title</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Link</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webinarCards as $webinarCard)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                @foreach($webinarCard->media as $media)
                                    <td style="text-align: center"><img id="image" src="{{ asset('storage/' . $media->path) }}" alt="Webinar Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                                @endforeach
                                <td style="text-align: center">{{ $webinarCard->webinar->webinar_name }}</td>
                                <td style="text-align: center">{{ $webinarCard->title }}</td>
                                <td id="description" style="text-align: center">{!!  substr($webinarCard->description, 0, 50) !!}</td>
                                <td style="text-align: center">
                                    <a href="{{ $webinarCard->link }}" target="_blank">
                                        <i class="fa-solid fa-link" style="cursor: pointer" id="link"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    @if ($webinarCard->status == true)
                                        <span class="badge bg-success" style="text-align: center">Active</span>
                                    @else
                                        <span class="badge bg-danger" style="text-align: center">Not Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                            <a href="{{ route('admin.webinar_cards.edit', $webinarCard->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $webinarCard->id }}, '{{ $webinarCard->webinar->webinar_name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $webinarCard->id }}"
                                                action="{{ route('admin.webinar_cards.destroy', $webinarCard->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
       //when click on link icon, open in new tab
       $('#image').on('click', function() {
        window.open($(this).attr('src'), '_blank');
       });      
    </script>
@endsection