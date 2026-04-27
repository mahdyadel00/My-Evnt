@extends('backend.partials.master')

@section('title', 'Edit feature')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.home-popup-features.index') }}">Features</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <form action="{{ route('admin.home-popup-features.update', $item->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Link event (optional)</label>
                        <select name="event_id" id="editHomePopupEventId" class="form-select"
                            data-placeholder="— Manual content (no event) —">
                            <option value=""></option>
                            @foreach ($events as $ev)
                                <option value="{{ $ev->id }}" @selected(old('event_id', $item->event_id) == $ev->id)>{{ $ev->name }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control"
                            value="{{ old('sort_order', $item->sort_order) }}" min="0">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Replace image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if ($item->image_path)
                            <small class="text-muted">Current: <a
                                    href="{{ asset('storage/' . $item->image_path) }}" target="_blank">view</a></small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location (manual)</label>
                        <input type="text" name="manual_location" class="form-control"
                            value="{{ old('manual_location', $item->manual_location) }}">
                    </div>
                    @include('backend.home_popup_features.partials.manual_datetime_fields', [
                        'pickerId' => 'editHomePopupDtPick',
                        'textId' => 'editHomePopupDtText',
                        'textValue' => old('manual_datetime_label', $item->manual_datetime_label),
                    ])
                    <div class="col-md-6 js-edit-home-popup-cta-fields">
                        <label class="form-label">Primary button text</label>
                        <input type="text" name="cta_label" class="form-control"
                            value="{{ old('cta_label', $item->cta_label) }}">
                    </div>
                    <div class="col-md-6 js-edit-home-popup-cta-fields">
                        <label class="form-label">Dismiss button text</label>
                        <input type="text" name="dismiss_label" class="form-control"
                            value="{{ old('dismiss_label', $item->dismiss_label) }}">
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="show_action_buttons" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_action_buttons"
                                id="editShowActionButtons" value="1"
                                @checked((string) old('show_action_buttons', $item->show_action_buttons ? '1' : '0') === '1')>
                            <label class="form-check-label" for="editShowActionButtons">Show buttons on public popup
                                (Get ticket / Maybe later)</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                @checked((string) old('is_active', $item->is_active ? '1' : '0') === '1')>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    @include('backend.home_popup_features.partials.manual_datetime_picker_script', [
        'pickerId' => 'editHomePopupDtPick',
        'textId' => 'editHomePopupDtText',
        'syncOnLoad' => true,
    ])
    <script>
        $(function () {
            if ($.fn.select2) {
                $('#editHomePopupEventId').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: $('#editHomePopupEventId').data('placeholder') || '— Manual content (no event) —'
                });
            }

            function syncEditHomePopupCtaFields() {
                var cb = document.getElementById('editShowActionButtons');
                var hide = !cb || !cb.checked;
                document.querySelectorAll('.js-edit-home-popup-cta-fields').forEach(function (el) {
                    el.classList.toggle('d-none', hide);
                });
            }
            var editShowBtns = document.getElementById('editShowActionButtons');
            if (editShowBtns) {
                editShowBtns.addEventListener('change', syncEditHomePopupCtaFields);
            }
            syncEditHomePopupCtaFields();
        });
    </script>
@endsection
