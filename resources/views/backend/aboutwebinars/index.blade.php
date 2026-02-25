@extends('backend.partials.master')

@section('title', 'Manage About Webinars')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage About Webinars</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Cities List</h5> -->
                @can('create about_webinars')
                    <a href="{{ route('admin.about_webinars.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New About Webinar
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
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aboutwebinars as $aboutwebinar)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                @foreach($aboutwebinar->media as $media)
                                    <td style="text-align: center"><img src="{{ asset('storage/' . $media->path) }}" alt="Webinar Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                                @endforeach
                                <td style="text-align: center">{{ $aboutwebinar->webinar->webinar_name }}</td>
                                <td style="text-align: center">{{ $aboutwebinar->title }}</td>
                                <td id="description" style="text-align: center">{!!  substr($aboutwebinar->description, 0, 50) !!}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                            <a href="{{ route('admin.about_webinars.edit', $aboutwebinar->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $aboutwebinar->id }}, '{{ $aboutwebinar->webinar->webinar_name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $aboutwebinar->id }}"
                                                action="{{ route('admin.about_webinars.destroy', $aboutwebinar->id) }}" method="POST"
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
       //show full description on click using tooltip
       $('td#description').tooltip({
        title: function() {
            return $(this).data('content');
        }
       });
    </script>
@endsection