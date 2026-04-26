@extends('backend.partials.master')

@section('title', 'Send SMS / WhatsApp')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.send.whatsapp') }}">Send SMS / WhatsApp</a>
                </li>
                <li class="breadcrumb-item active">Send SMS / WhatsApp</li>
            </ol>
        </nav>
        <!-- Users List Table -->
        @include('backend.partials._message')
        <form id="send-outbound-message-form" action="{{ route('admin.send.whatsapp.post') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('POST')
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="main-content-label mg-b-5">
                                <div class="col-xs-12 col-sm-12 col-md-12 d-flex justify-content-between flex-wrap">
                                    <div class="form-group col-md-12 col-12 mt-2 mb-3">
                                        <p class="card-title mb-75">Channel</p>
                                        <div class="d-flex flex-wrap gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="channel"
                                                    id="channel_whatsapp" value="whatsapp"
                                                    {{ old('channel', 'whatsapp') === 'whatsapp' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="channel_whatsapp">WhatsApp</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="channel"
                                                    id="channel_sms" value="sms"
                                                    {{ old('channel') === 'sms' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="channel_sms">SMS (SMS Misr)</label>
                                            </div>
                                        </div>
                                        @if ($errors->has('channel'))
                                            <span class="text-danger d-block mt-1" role="alert">
                                                <strong>{{ $errors->first('channel') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @php
                                        $oldUserIds = array_map('strval', (array) old('user_ids', []));
                                    @endphp
                                    <div class="form-group col-md-12 col-12 mt-2 mb-3">
                                        <p class="card-title mb-75">Users</p>
                                        <select class="form-control select2-users" name="user_ids[]" id="user_ids"
                                            multiple required data-placeholder="Search and select users…">
                                            <option value=""></option>
                                            @foreach ($users as $user)
                                                @php
                                                    $userLabel = trim($user->first_name . ' ' . $user->last_name);
                                                    $phoneDisplay = $user->phone ? (string) $user->phone : __('no phone');
                                                @endphp
                                                <option value="{{ $user->id }}"
                                                    {{ in_array((string) $user->id, $oldUserIds, true) ? 'selected' : '' }}>
                                                    {{ $userLabel ?: 'ID ' . $user->id }} — {{ $phoneDisplay }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('user_ids') || $errors->has('user_ids.*'))
                                            <span class="text-danger d-block mt-1" role="alert">
                                                <strong>{{ $errors->first('user_ids') ?: $errors->first('user_ids.*') }}</strong>
                                            </span>
                                        @endif
                                        <small class="text-muted d-block mt-1">Type to search by name or phone; you can
                                            select multiple users.</small>
                                    </div>
                                    <div class="form-group col-md-12 col-12 mt-2 mb-2">
                                        <p class="card-title mb-75">Message</p>
                                        <textarea name="message" id="message" class="form-control" rows="6"
                                            placeholder="Message" required>{{ old('message') }}</textarea>
                                        @if ($errors->has('message'))
                                            <span class="text-danger d-block mt-1" role="alert">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-3">
                                    <button class="btn btn-primary btn-sm pd-x-20" type="submit">
                                        <i class="ti ti-device-floppy ti-xs">Send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
        <!-- / Content -->
@endsection

@section('js')
    <script>
        (function () {
            if (typeof $ === 'undefined') {
                return;
            }
            $('#user_ids').select2({
                width: '100%',
                placeholder: $('#user_ids').data('placeholder') || 'Search and select users…',
                allowClear: true,
                closeOnSelect: false,
            });

            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('message', {
                    height: 220,
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_P,
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['HorizontalRule'] },
                        { name: 'styles', items: ['Format'] },
                        { name: 'document', items: ['Source'] },
                    ],
                });
            }

            $('#send-outbound-message-form').on('submit', function (e) {
                e.preventDefault();

                if (typeof Swal === 'undefined') {
                    window.alert('SweetAlert2 is not loaded.');
                    return;
                }

                var $form = $(this);
                var $btn = $form.find('button[type="submit"]');

                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.message) {
                    CKEDITOR.instances.message.updateElement();
                }

                var formData = new FormData(this);
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                $btn.prop('disabled', true);

                Swal.fire({
                    title: 'Sending…',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: function () {
                        Swal.showLoading();
                    },
                });

                fetch($form.attr('action'), {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                    credentials: 'same-origin',
                })
                    .then(function (response) {
                        return response.json().then(function (json) {
                            return { response: response, json: json };
                        }).catch(function () {
                            return { response: response, json: {} };
                        });
                    })
                    .then(function (result) {
                        Swal.close();
                        var response = result.response;
                        var json = result.json || {};

                        if (response.status === 422) {
                            var errs = json.errors ? Object.values(json.errors).flat() : [];
                            Swal.fire({
                                icon: 'error',
                                title: json.message || 'Validation',
                                text: errs.length ? errs.join('\n') : (json.message || 'Invalid input.'),
                            });
                            return;
                        }

                        if (!response.ok) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: json.message || 'Request failed.',
                            });
                            return;
                        }

                        var alertType = json.alert_type || 'success';
                        var icon = alertType === 'success' ? 'success' : (alertType === 'warning' ? 'warning' : 'error');
                        var title = alertType === 'success' ? 'Sent' : (alertType === 'warning' ? 'Partially sent' : 'Failed');
                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: json.message || '',
                        });

                        if (alertType === 'success' && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.message) {
                            CKEDITOR.instances.message.setData('');
                        }
                    })
                    .catch(function () {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Network error',
                            text: 'Please try again.',
                        });
                    })
                    .finally(function () {
                        $btn.prop('disabled', false);
                    });
            });
        })();
    </script>
@endsection