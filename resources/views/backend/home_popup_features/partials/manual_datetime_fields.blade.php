{{-- Date/time picker fills manual_datetime_label (same field as before). --}}
<div class="col-md-6">
    <label class="form-label">Pick date and time</label>
    <input type="datetime-local" class="form-control mb-1" id="{{ $pickerId }}" autocomplete="off">
    <small class="text-muted d-block mb-2">Choose a date/time here to fill the label below automatically.</small>
    <label class="form-label">Date & time label (manual)</label>
    <input type="text" name="manual_datetime_label" id="{{ $textId }}" class="form-control"
        value="{{ $textValue }}" placeholder="e.g. Wed, Jun 11, 8:00 PM">
</div>
