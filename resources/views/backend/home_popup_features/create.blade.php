@extends('backend.partials.master')

@section('title', 'Add feature')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.home-popup-features.index') }}">Features</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
        @include('backend.partials._message')
        <form action="{{ route('admin.home-popup-features.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Link event (optional)</label>
                        <select name="event_id" id="createHomePopupEventId" class="form-select"
                            data-placeholder="— Manual content (no event) —">
                            <option value=""></option>
                            @foreach ($events as $ev)
                                <option value="{{ $ev->id }}" @selected(old('event_id') == $ev->id)>{{ $ev->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">If empty, fill title + description below.</small>
                        @error('event_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}"
                            min="0">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Title (override or required when no event)</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                            placeholder="Shown when set; otherwise event name">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description (override or required when no event)</label>
                        <textarea name="description" class="form-control" rows="4"
                            placeholder="Plain text or HTML">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Popup image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location (manual)</label>
                        <input type="text" name="manual_location" class="form-control"
                            value="{{ old('manual_location') }}" placeholder="If no event or to override">
                    </div>
                    @include('backend.home_popup_features.partials.manual_datetime_fields', [
                        'pickerId' => 'createHomePopupDtPick',
                        'textId' => 'createHomePopupDtText',
                        'textValue' => old('manual_datetime_label'),
                    ])
                    <div class="col-md-6 js-create-home-popup-cta-fields">
                        <label class="form-label">Primary button text</label>
                        <input type="text" name="cta_label" class="form-control" value="{{ old('cta_label', 'Get Ticket') }}">
                    </div>
                    <div class="col-md-6 js-create-home-popup-cta-fields">
                        <label class="form-label">Dismiss button text</label>
                        <input type="text" name="dismiss_label" class="form-control"
                            value="{{ old('dismiss_label', 'Maybe Later') }}">
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="show_action_buttons" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_action_buttons"
                                id="createShowActionButtons" value="1"
                                @checked((string) old('show_action_buttons', '1') === '1')>
                            <label class="form-check-label" for="createShowActionButtons">Show buttons on public popup
                                (Get ticket / Maybe later)</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                @checked((string) old('is_active', '1') === '1')>
                            <label class="form-check-label" for="is_active">Active (show on homepage)</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    @include('backend.home_popup_features.partials.manual_datetime_picker_script', [
        'pickerId' => 'createHomePopupDtPick',
        'textId' => 'createHomePopupDtText',
        'syncOnLoad' => true,
    ])
    <script>
        $(function () {
            if ($.fn.select2) {
                $('#createHomePopupEventId').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: $('#createHomePopupEventId').data('placeholder') || '— Manual content (no event) —'
                });
            }

            function syncCreateHomePopupCtaFields() {
                var cb = document.getElementById('createShowActionButtons');
                var hide = !cb || !cb.checked;
                document.querySelectorAll('.js-create-home-popup-cta-fields').forEach(function (el) {
                    el.classList.toggle('d-none', hide);
                });
            }
            var createShowBtns = document.getElementById('createShowActionButtons');
            if (createShowBtns) {
                createShowBtns.addEventListener('change', syncCreateHomePopupCtaFields);
            }
            syncCreateHomePopupCtaFields();
        });
    </script>
@endsection
