@extends('backend.partials.master')

@section('title', 'Manage Companies')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Manage Companies</li>
            </ol>
        </nav>
        @include('backend.partials._message')

        <div class="card">
            <div class="card-header border-bottom">
                <!-- <h5 class="card-title mb-0">Companies List</h5> -->
                @can('create company')
                    <a href="{{ route('admin.companies.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-plus ti-xs me-1"></i> Add New Company
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table id="example2" class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">Logo</th>
                            <th style="text-align: center">Company Name</th>
                            <th style="text-align: center">Company Email</th>
                            <th style="text-align: center">Company Phone</th>
                            <th style="text-align: center">Company WhatsApp</th>
                            <th style="text-align: center">Company Website</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">
                                    @php
                                        $logo = $company->media->firstWhere('name', 'logo');
                                    @endphp
                                    @if ($logo)
                                        <a href="{{ asset('storage/' . $logo->path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $logo->path) }}" alt="Company Logo"
                                                class="company-logo" width="50" height="50" style="border-radius: 50%;">
                                        </a>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{{ route('admin.companies.show', $company->id) }}">{{ $company->company_name }}</a>
                                </td>
                                <td style="text-align: center">
                                    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                </td>
                                <td style="text-align: center">
                                    <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                </td>
                                <td style="text-align: center">
                                    <a href="https://wa.me/{{ $company->whats_app }}" target="_blank">
                                        <i class="ti ti-brand-whatsapp ti-sm"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <a href="{{ $company->website }}" target="_blank">
                                        <i class="ti ti-world ti-sm"></i>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    @if ($company->status == 1)
                                        <span class="badge bg-success" style="text-align: center">Active</span>
                                    @else
                                        <span class="badge bg-danger" style="text-align: center">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex justify-content-center gap-2" style="text-align: center">
                                        @can('view company')
                                            <a href="{{ route('admin.companies.show', $company->id) }}"
                                                class="btn btn-xs btn-outline-warning" style="text-align: center" title="View">
                                                <i class="ti ti-eye ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('update company')
                                            <a href="{{ route('admin.companies.edit', $company->id) }}"
                                                class="btn btn-xs btn-outline-info" style="text-align: center" title="Edit">
                                                <i class="ti ti-pencil ti-sm"></i>
                                            </a>
                                        @endcan
                                        @can('delete company')
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                onclick="confirmDelete({{ $company->id }}, '{{ $company->company_name }}')"
                                                style="text-align: center" title="Delete">
                                                <i class="ti ti-trash ti-sm"></i>
                                            </button>
                                            <form id="delete-form-{{ $company->id }}"
                                                action="{{ route('admin.companies.destroy', $company->id) }}" method="POST"
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
        </div>
    </div>
@endsection