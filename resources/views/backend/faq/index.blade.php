@extends('backend.partials.master')

@section('title', 'FAQs')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">FAQs</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                @can('create faq')
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs"> Add FAQ</i>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Question</th>   
                            <th style="text-align: center">Answer</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($faqs as $faq)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>
                                    {{ $faq->question }}
                                </td>
                                <td>
                                    {{ $faq->answer }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('update faq')
                                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" style="margin: 0 5px;"
                                                class="btn btn-xs btn-outline-info">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete faq')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $faq->id }}, '{{ $faq->question }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $faq->id }}"
                                                    action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')   
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Offcanvas to add new user -->
        </div>
    </div>
    <!-- / Content -->
@endsection