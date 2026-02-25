@extends('backend.partials.master')

@section('title', 'Manage Webinar Speakers')

@section('content')
    <div class="container-xxl flex-grow-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Webinar Speakers</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Cities List</h5> -->
                @can('create webinar_speakers')
                    <a href="{{ route('admin.webinar_speakers.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Webinar
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Image</th>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Job Title</th>
                            <th style="text-align: center">Webinar</th>
                            <th style="text-align: center">About Webinar</th>
                            <th style="text-align: center">Description</th>
                            <!-- <th style="text-align: center">Facebook</th>
                                <th style="text-align: center">Twitter</th>
                                <th style="text-align: center">Linkedin</th>
                                <th style="text-align: center">Instagram</th>
                                <th style="text-align: center">Youtube</th> -->
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webinarSpeakers as $webinarSpeaker)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                @foreach($webinarSpeaker->media as $media)
                                    <td style="text-align: center"><img src="{{ asset('storage/' . $media->path) }}"
                                            alt="Webinar Image"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                                @endforeach
                                <td style="text-align: center">{{ $webinarSpeaker->name }}</td>
                                <td style="text-align: center">{{ $webinarSpeaker->job_title }}</td>
                                <td style="text-align: center">{{ $webinarSpeaker->webinar?->webinar_name }}</td>
                                <td style="text-align: center">{{ $webinarSpeaker->aboutwebinar?->title }}</td>
                                <td style="text-align: center">{!!  substr($webinarSpeaker->description, 0, 50) !!}</td>
                                <!-- <td style="text-align: center">{{ $webinarSpeaker->facebook }}</td>
                                        <td style="text-align: center">{{ $webinarSpeaker->twitter }}</td>
                                        <td style="text-align: center">{{ $webinarSpeaker->linkedin }}</td>
                                        <td style="text-align: center">{{ $webinarSpeaker->instagram }}</td>
                                        <td style="text-align: center">{{ $webinarSpeaker->youtube }}</td>                                -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('update webinar_speakers')
                                            <a href="{{ route('admin.webinar_speakers.edit', $webinarSpeaker->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete webinar_speakers')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $webinarSpeaker->id }}, '{{ $webinarSpeaker->name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                        @endcan
                                        <form id="delete-form-{{ $webinarSpeaker->id }}"
                                            action="{{ route('admin.webinar_speakers.destroy', $webinarSpeaker->id) }}"
                                            method="POST" style="display: none;">
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