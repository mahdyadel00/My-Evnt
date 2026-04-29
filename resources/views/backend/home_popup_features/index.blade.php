@extends('backend.partials.master')

@section('title', 'Features')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Features</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-sm btn-outline-primary" id="jsHomePopupAddBtn">
                    <i class="ti ti-plus ti-xs"></i> Add feature
                </button>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table border-top table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Event</th>
                            <th>Active</th>
                            <th>Sort</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="jsHomePopupTableBody">
                        @forelse ($items as $row)
                            <tr data-feature-id="{{ $row->id }}">
                                <td class="js-col-idx">{{ $loop->iteration }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($row->resolveTitle(), 40) }}</td>
                                <td>{{ $row->event ? \Illuminate\Support\Str::limit($row->event->name, 30) : '—' }}</td>
                                <td>{{ $row->is_active ? 'Yes' : 'No' }}</td>
                                <td>{{ $row->sort_order }}</td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-outline-info js-home-popup-edit"
                                        data-id="{{ $row->id }}"><i class="ti ti-pencil ti-sm"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger js-home-popup-delete"
                                        data-id="{{ $row->id }}"><i class="ti ti-trash ti-sm"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr id="jsHomePopupEmptyRow">
                                <td colspan="6">No records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="jsHomePopupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jsHomePopupModalTitle">Add feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="jsHomePopupForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="jsHomePopupSpoofMethod" value=""
                            autocomplete="off">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Link event (optional)</label>
                                <select name="event_id" id="jsHomePopupEventId" class="form-select" data-placeholder="— Manual content (no event) —">
                                    <option value=""></option>
                                    @foreach ($events as $ev)
                                        <option value="{{ $ev->id }}">{{ $ev->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">If empty, fill title + description.</small>
                                <div class="text-danger small d-none" id="jsHomePopupErrEvent"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sort order</label>
                                <input type="number" name="sort_order" id="jsHomePopupSort" class="form-control"
                                    value="0" min="0">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="jsHomePopupTitle" class="form-control"
                                    placeholder="Override or required when no event">
                                <div class="text-danger small d-none" id="jsHomePopupErrTitle"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="jsHomePopupDescription" class="form-control" rows="4"
                                    placeholder="Plain text or HTML"></textarea>
                                <div class="text-danger small d-none" id="jsHomePopupErrDescription"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Popup image</label>
                                <input type="file" name="image" id="jsHomePopupImage" class="form-control" accept="image/*" required>
                                <div class="small text-muted mt-1 d-none" id="jsHomePopupCurrentImageWrap">
                                    Current: <a href="#" id="jsHomePopupCurrentImageLink" target="_blank">view</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location (manual)</label>
                                <input type="text" name="manual_location" id="jsHomePopupLoc" class="form-control">
                            </div>
                            @include('backend.home_popup_features.partials.manual_datetime_fields', [
                                'pickerId' => 'jsHomePopupDtLocal',
                                'textId' => 'jsHomePopupDt',
                                'textValue' => '',
                            ])
                            <div class="col-md-6 js-home-popup-cta-fields">
                                <label class="form-label">Primary button text</label>
                                <input type="text" name="cta_label" id="jsHomePopupCtaLabel" class="form-control"
                                    value="Get Ticket">
                            </div>
                            <div class="col-md-6 js-home-popup-cta-fields">
                                <label class="form-label">Dismiss button text</label>
                                <input type="text" name="dismiss_label" id="jsHomePopupDismiss" class="form-control"
                                    value="Maybe Later">
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="show_action_buttons" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="show_action_buttons"
                                        id="jsHomePopupShowButtons" value="1" checked>
                                    <label class="form-check-label" for="jsHomePopupShowButtons">Show buttons on public
                                        popup (Get ticket / Maybe later)</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="is_active" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="jsHomePopupActive" value="1" checked>
                                    <label class="form-check-label" for="jsHomePopupActive">Active</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="jsHomePopupSaveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        (function () {
            var baseUrl = @json(url('admin/home-popup-features'));
            var storeUrl = @json(route('admin.home-popup-features.store'));
            var csrf = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrf ? csrf.getAttribute('content') : '';
            var form = document.getElementById('jsHomePopupForm');
            var modalEl = document.getElementById('jsHomePopupModal');
            var tbody = document.getElementById('jsHomePopupTableBody');
            var spoof = document.getElementById('jsHomePopupSpoofMethod');
            var mode = 'create';
            var currentId = null;
            var modal =
                modalEl && typeof bootstrap !== 'undefined' ? new bootstrap.Modal(modalEl) : null;

            function setHomePopupEventSelectValue(val) {
                var v = val ? String(val) : '';
                if (typeof jQuery !== 'undefined' && jQuery('#jsHomePopupEventId').length) {
                    jQuery('#jsHomePopupEventId').val(v).trigger('change');
                    return;
                }
                var el = document.getElementById('jsHomePopupEventId');
                if (el) {
                    el.value = v;
                }
            }

            function pad2(n) {
                return String(n).padStart(2, '0');
            }

            function formatFromLocal(isoLocal) {
                if (!isoLocal) {
                    return '';
                }
                var d = new Date(isoLocal);
                if (isNaN(d.getTime())) {
                    return '';
                }
                try {
                    return new Intl.DateTimeFormat(document.documentElement.lang || 'en', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    }).format(d);
                } catch (e) {
                    return d.toString();
                }
            }

            function toLocalValue(d) {
                return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) + 'T' + pad2(d.getHours()) +
                    ':' + pad2(d.getMinutes());
            }

            function trySyncPickerFromLabel(picker, textEl) {
                if (!picker || !textEl) {
                    return;
                }
                var t = String(textEl.value || '').trim();
                if (!t) {
                    picker.value = '';
                    return;
                }
                var ms = Date.parse(t);
                if (!isNaN(ms)) {
                    picker.value = toLocalValue(new Date(ms));
                } else {
                    picker.value = '';
                }
            }

            (function wireModalDatetimePicker() {
                var p = document.getElementById('jsHomePopupDtLocal');
                var t = document.getElementById('jsHomePopupDt');
                if (!p || !t) {
                    return;
                }
                p.addEventListener('change', function () {
                    if (p.value) {
                        t.value = formatFromLocal(p.value);
                    }
                });
            })();

            function clearFieldErrors() {
                ['jsHomePopupErrEvent', 'jsHomePopupErrTitle', 'jsHomePopupErrDescription'].forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.classList.add('d-none');
                        el.textContent = '';
                    }
                });
            }

            function showValidationErrors(payload) {
                clearFieldErrors();
                if (!payload || !payload.errors) return;
                Object.keys(payload.errors).forEach(function (key) {
                    var msg = (payload.errors[key] || []).join(' ');
                    if (key === 'event_id') {
                        var e = document.getElementById('jsHomePopupErrEvent');
                        if (e) {
                            e.textContent = msg;
                            e.classList.remove('d-none');
                        }
                    }
                    if (key === 'title') {
                        var t = document.getElementById('jsHomePopupErrTitle');
                        if (t) {
                            t.textContent = msg;
                            t.classList.remove('d-none');
                        }
                    }
                    if (key === 'description') {
                        var d = document.getElementById('jsHomePopupErrDescription');
                        if (d) {
                            d.textContent = msg;
                            d.classList.remove('d-none');
                        }
                    }
                });
            }

            function escapeHtml(s) {
                if (s == null) return '';
                return String(s).replace(/[&<>"']/g, function (c) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    })[c];
                });
            }

            function buildRowTr(row) {
                var tr = document.createElement('tr');
                tr.setAttribute('data-feature-id', row.id);
                tr.innerHTML =
                    '<td class="js-col-idx">0</td>' +
                    '<td>' + escapeHtml(row.title) + '</td>' +
                    '<td>' + escapeHtml(row.event) + '</td>' +
                    '<td>' + escapeHtml(row.active) + '</td>' +
                    '<td>' + escapeHtml(row.sort) + '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-xs btn-outline-info js-home-popup-edit" data-id="' + row.id +
                    '"><i class="ti ti-pencil ti-sm"></i></button> ' +
                    '<button type="button" class="btn btn-xs btn-outline-danger js-home-popup-delete" data-id="' +
                    row.id + '"><i class="ti ti-trash ti-sm"></i></button>' +
                    '</td>';
                return tr;
            }

            function renumberRows() {
                var rows = tbody.querySelectorAll('tr[data-feature-id]');
                rows.forEach(function (tr, i) {
                    var c = tr.querySelector('.js-col-idx');
                    if (c) c.textContent = String(i + 1);
                });
            }

            function removeEmptyRow() {
                var empty = document.getElementById('jsHomePopupEmptyRow');
                if (empty) empty.remove();
            }

            function resetFormForCreate() {
                mode = 'create';
                currentId = null;
                clearFieldErrors();
                form.reset();
                var spoofReset = document.getElementById('jsHomePopupSpoofMethod');
                spoofReset.setAttribute('name', '_method');
                spoofReset.value = '';
                setHomePopupEventSelectValue('');
                document.getElementById('jsHomePopupSort').value = '0';
                document.getElementById('jsHomePopupCtaLabel').value = 'Get Ticket';
                document.getElementById('jsHomePopupDismiss').value = 'Maybe Later';
                document.getElementById('jsHomePopupActive').checked = true;
                var showBtns = document.getElementById('jsHomePopupShowButtons');
                if (showBtns) {
                    showBtns.checked = true;
                }
                document.getElementById('jsHomePopupImage').value = '';
                document.getElementById('jsHomePopupCurrentImageWrap').classList.add('d-none');
                document.getElementById('jsHomePopupModalTitle').textContent = 'Add feature';
                var dtPick = document.getElementById('jsHomePopupDtLocal');
                if (dtPick) {
                    dtPick.value = '';
                }
                syncHomePopupCtaFieldsVisibility();
            }

            function openCreate() {
                resetFormForCreate();
                if (modal) modal.show();
            }

            function fillForm(item) {
                setHomePopupEventSelectValue(item.event_id ? String(item.event_id) : '');
                document.getElementById('jsHomePopupSort').value = String(item.sort_order ?? 0);
                document.getElementById('jsHomePopupTitle').value = item.title || '';
                document.getElementById('jsHomePopupDescription').value = item.description || '';
                document.getElementById('jsHomePopupLoc').value = item.manual_location || '';
                var dtText = document.getElementById('jsHomePopupDt');
                var dtPick = document.getElementById('jsHomePopupDtLocal');
                dtText.value = item.manual_datetime_label || '';
                trySyncPickerFromLabel(dtPick, dtText);
                document.getElementById('jsHomePopupCtaLabel').value = item.cta_label || 'Get Ticket';
                document.getElementById('jsHomePopupDismiss').value = item.dismiss_label || 'Maybe Later';
                document.getElementById('jsHomePopupActive').checked = !!item.is_active;
                var showBtnsEl = document.getElementById('jsHomePopupShowButtons');
                if (showBtnsEl) {
                    showBtnsEl.checked = item.show_action_buttons !== false && item.show_action_buttons !== '0';
                }
                document.getElementById('jsHomePopupImage').value = '';
                var wrap = document.getElementById('jsHomePopupCurrentImageWrap');
                var link = document.getElementById('jsHomePopupCurrentImageLink');
                if (item.image_url) {
                    wrap.classList.remove('d-none');
                    link.href = item.image_url;
                } else {
                    wrap.classList.add('d-none');
                }
                syncHomePopupCtaFieldsVisibility();
            }

            function syncHomePopupCtaFieldsVisibility() {
                var cb = document.getElementById('jsHomePopupShowButtons');
                var hide = !cb || !cb.checked;
                document.querySelectorAll('#jsHomePopupForm .js-home-popup-cta-fields').forEach(function (el) {
                    el.classList.toggle('d-none', hide);
                });
            }

            var showButtonsCb = document.getElementById('jsHomePopupShowButtons');
            if (showButtonsCb) {
                showButtonsCb.addEventListener('change', syncHomePopupCtaFieldsVisibility);
            }
            syncHomePopupCtaFieldsVisibility();

            document.getElementById('jsHomePopupAddBtn').addEventListener('click', openCreate);

            tbody.addEventListener('click', function (e) {
                var editBtn = e.target.closest('.js-home-popup-edit');
                if (editBtn) {
                    var id = editBtn.getAttribute('data-id');
                    mode = 'update';
                    currentId = id;
                    clearFieldErrors();
                    document.getElementById('jsHomePopupModalTitle').textContent = 'Edit feature';
                    document.getElementById('jsHomePopupSpoofMethod').value = 'PUT';
                    Swal.fire({
                        title: 'Loading…',
                        allowOutsideClick: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });
                    fetch(baseUrl + '/' + id + '/edit', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    }).then(function (r) {
                        return r.json().then(function (j) {
                            return {
                                r: r,
                                j: j
                            };
                        });
                    }).then(function (x) {
                        Swal.close();
                        if (!x.r.ok || !x.j.success) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: (x.j && x.j.message) || 'Failed to load'
                            });
                            return;
                        }
                        fillForm(x.j.item);
                        if (modal) modal.show();
                    }).catch(function () {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Network error'
                        });
                    });
                    return;
                }
                var delBtn = e.target.closest('.js-home-popup-delete');
                if (delBtn) {
                    var delId = delBtn.getAttribute('data-id');
                    Swal.fire({
                        title: 'Delete?',
                        text: 'This cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete'
                    }).then(function (res) {
                        if (!res.isConfirmed) return;
                        var delFd = new FormData();
                        delFd.append('_method', 'DELETE');
                        delFd.append('_token', csrfToken);
                        fetch(baseUrl + '/' + delId, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'same-origin',
                            body: delFd
                        }).then(function (r) {
                            return r.json().then(function (j) {
                                return {
                                    r: r,
                                    j: j
                                };
                            });
                        }).then(function (x) {
                            if (x.r.status === 422) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: (x.j.message) || 'Validation failed'
                                });
                                return;
                            }
                            if (!x.r.ok || !x.j.success) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: (x.j && x.j.message) || 'Delete failed'
                                });
                                return;
                            }
                            var tr = tbody.querySelector('tr[data-feature-id="' + delId + '"]');
                            if (tr) tr.remove();
                            renumberRows();
                            if (!tbody.querySelector('tr[data-feature-id]')) {
                                tbody.innerHTML =
                                    '<tr id="jsHomePopupEmptyRow"><td colspan="6">No records yet.</td></tr>';
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Done',
                                text: x.j.message || 'Deleted'
                            });
                        }).catch(function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Network error'
                            });
                        });
                    });
                }
            });

            document.getElementById('jsHomePopupSaveBtn').addEventListener('click', function () {
                if (typeof Swal === 'undefined') {
                    alert('SweetAlert2 is not loaded.');
                    return;
                }
                clearFieldErrors();
                var spoofEl = document.getElementById('jsHomePopupSpoofMethod');
                if (mode === 'update' && currentId) {
                    spoofEl.setAttribute('name', '_method');
                    spoofEl.value = 'PUT';
                } else {
                    spoofEl.removeAttribute('name');
                    spoofEl.value = '';
                }
                var fd = new FormData(form);
                if (mode === 'create') {
                    spoofEl.setAttribute('name', '_method');
                }
                var url = mode === 'create' ? storeUrl : baseUrl + '/' + currentId;
                Swal.fire({
                    title: 'Saving…',
                    allowOutsideClick: false,
                    didOpen: function () {
                        Swal.showLoading();
                    }
                });
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: fd,
                    credentials: 'same-origin'
                }).then(function (r) {
                    return r.json().then(function (j) {
                        return {
                            r: r,
                            j: j
                        };
                    }).catch(function () {
                        return {
                            r: r,
                            j: {}
                        };
                    });
                }).then(function (x) {
                    Swal.close();
                    if (x.r.status === 422) {
                        showValidationErrors(x.j);
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation',
                            text: (x.j.message) || 'Please fix the form.'
                        });
                        return;
                    }
                    if (!x.r.ok || !x.j.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: (x.j && x.j.message) || 'Save failed'
                        });
                        return;
                    }
                    removeEmptyRow();
                    var row = x.j.row;
                    if (mode === 'create' && row) {
                        tbody.appendChild(buildRowTr(row));
                    } else if (mode === 'update' && row && currentId) {
                        var oldTr = tbody.querySelector('tr[data-feature-id="' + currentId + '"]');
                        if (oldTr) {
                            oldTr.replaceWith(buildRowTr(row));
                        }
                    }
                    renumberRows();
                    if (modal) modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Done',
                        text: x.j.message || 'Saved'
                    });
                }).catch(function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Network error'
                    });
                });
            });

            if (typeof jQuery !== 'undefined' && jQuery.fn.select2 && modalEl) {
                jQuery(modalEl).on('shown.bs.modal', function () {
                    var $ev = jQuery('#jsHomePopupEventId');
                    if (!$ev.length || $ev.hasClass('select2-hidden-accessible')) {
                        return;
                    }
                    $ev.select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: $ev.data('placeholder') || '— Manual content (no event) —',
                        dropdownParent: jQuery('#jsHomePopupModal')
                    });
                });
            }
        })();
    </script>
@endsection
