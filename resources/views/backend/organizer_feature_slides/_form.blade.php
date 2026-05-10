@php
    $slide = $slide ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('Sort order') }}</label>
        <input type="number" name="sort_order" class="form-control" min="0" step="1"
            value="{{ old('sort_order', $slide->sort_order ?? 0) }}">
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                @checked((string) old('is_active', $slide === null || $slide->is_active ? '1' : '0') === '1')>
            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required
        value="{{ old('title', $slide->title ?? '') }}">
    @error('title')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="subtitle" class="form-control" rows="5">{{ old('subtitle', $slide->subtitle ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('Image (upload)') }}</label>
        <input type="file" name="hero_image" class="form-control" accept="image/*">
        @error('hero_image')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
        @if ($slide && $slide->hero_image)
            <small class="text-muted d-block mt-1">{{ __('Leave empty to keep current.') }}</small>
            <div class="mt-2">
                <img src="{{ $slide->heroImageSrc() }}" alt="" class="rounded" style="max-height:120px;width:auto;">
            </div>
        @endif
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">{{ __('Image URL (optional)') }}</label>
        <input type="url" name="hero_image_remote" class="form-control" placeholder="https://..."
            value="{{ old('hero_image_remote', $slide && \Illuminate\Support\Str::startsWith((string) $slide->hero_image, ['http://', 'https://']) ? $slide->hero_image : '') }}">
        <small class="text-muted">{{ __('Used when no file is uploaded.') }}</small>
    </div>
</div>
