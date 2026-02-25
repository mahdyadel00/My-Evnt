@extends('backend.partials.master')

@section('title', 'Sliders')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Sliders</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                {{-- @can('create slider') --}}
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-plus ti-xs"> Add Slider</i>
                </a>
                {{-- @endcan --}}

            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Small Image</th>
                            <th style="text-align: center">Title</th>
                            <th style="text-align: center">Description</th>
                            <th style="text-align: center">Url</th>
                            <th style="text-align: center">Event</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sliders as $slider)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>
                                    @foreach ($slider->media as $media)
                                        @if ($media->name == 'image')
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%;" id="image">
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($slider->media as $media)
                                        @if ($media->name == 'image_small')
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="" style="width: 50px; height: 50px;" id="image_small">
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $slider->title }}</td>
                                <td>{!! substr($slider->description, 0, 20) !!}...</td>
                                <td>
                                    <!-- icon -->
                                    <a href="{{ $slider->url }}" target="_blank">
                                        <i class="fa-solid fa-link"></i>
                                    </a>
                                </td>
                                <td>{{ substr($slider->event?->name, 0, 20) }}...</td>
                                <td>
                                    @can('update slider')
                                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-xs btn-outline-info"
                                            title="Edit">
                                            <i class="ti ti-pencil ti-sm" title="Edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete slider')
                                        <button type="button" class="btn btn-xs btn-outline-danger"
                                            onclick="confirmDelete({{ $slider->id }}, '{{ $slider->title }}')" title="Delete">
                                            <i class="ti ti-trash ti-sm"></i>
                                        </button>
                                        <form id="delete-form-{{ $slider->id }}"
                                            action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
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
        //when click on image show full image
        $('#image').click(function() {
            window.open($(this).attr('src'), '_blank');
        });
        $('#image_small').click(function() {
            window.open($(this).attr('src'), '_blank');
        });
    </script>
@endsection