@extends('backend.partials.master')

@section('title', 'Visitor Sessions')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Visitor Sessions</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                {{-- @can('create slider') --}}
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Session ID</th>
                            <th style="text-align: center">User ID</th>
                            <th style="text-align: center">Event ID</th>
                            <th style="text-align: center">IP Address</th>
                            <th style="text-align: center">User Agent</th>
                            <th style="text-align: center">Device</th>
                            <th style="text-align: center">Browser</th>
                            <th style="text-align: center">OS</th>
                            <th style="text-align: center">Country</th>
                            <th style="text-align: center">City</th>
                            <th style="text-align: center">Referrer</th>
                            <th style="text-align: center">Page Views</th>
                            <th style="text-align: center">Visited At</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sessions as $session)
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }} </td>
                                <td style="text-align: center;">
                                    {{ $session->session_id }}
                                </td>
                                <td style="text-align: center;">{{ $session->user?->name ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ $session->event?->name ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ $session->ip_address }}</td>
                                <td style="text-align: center;">{{ substr($session->user_agent, 0, 20) }}...   </td>
                                <td style="text-align: center;">{{ $session->device }}</td>
                                <td style="text-align: center;">{{ substr($session->browser, 0, 20) }}...   </td>
                                <td style="text-align: center;">{{ substr($session->os, 0, 20) }}...   </td>
                                <td style="text-align: center;">
                                    {{ $session->getCountryFromIp() ?? 'N/A' }}
                                </td>
                                <td style="text-align: center;">
                                    {{ $session->getCityFromIp() ?? 'N/A' }}
                                </td>
                                <td style="text-align: center;">{{ $session->referrer }}</td>
                                <td style="text-align: center;" >{{ $session->page_views }}</td>
                                <td style="text-align: center;">{{ $session->visited_at->format('d-m-Y H:i:s') ?? 'N/A' }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.sessions.destroy', $session->id) }}" class="btn btn-xs btn-outline-danger"
                                        title="Delete">
                                        <i class="ti ti-trash ti-sm"></i>
                                    </a>
                                    <form id="delete-form-{{ $session->id }}"
                                        action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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