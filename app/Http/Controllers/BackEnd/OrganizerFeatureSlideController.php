<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrganizerFeatureSlide\StoreOrganizerFeatureSlideRequest;
use App\Http\Requests\Backend\OrganizerFeatureSlide\UpdateOrganizerFeatureSlideRequest;
use App\Models\OrganizerFeatureSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrganizerFeatureSlideController extends Controller
{
    public function index(): View
    {
        $slides = OrganizerFeatureSlide::query()->ordered()->get();

        return view('backend.organizer_feature_slides.index', compact('slides'));
    }

    public function create(): View
    {
        return view('backend.organizer_feature_slides.create');
    }

    public function store(StoreOrganizerFeatureSlideRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            /** @var array<string, mixed> $data */
            $data = $request->safe()->except(['hero_image', 'hero_image_remote']);
            $data['hero_image'] = $this->resolveHeroImageValue($request, null);
            $data['sort_order'] = (int) ($request->input('sort_order')
                ?? (int) OrganizerFeatureSlide::query()->max('sort_order') + 1);
            $data['is_active'] = $request->boolean('is_active');

            OrganizerFeatureSlide::query()->create($data);

            DB::commit();

            return redirect()
                ->route('admin.organizer-feature-slides.index')
                ->with('success', __('Organizer slide created successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('error')->error('OrganizerFeatureSlideController@store: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', __('Something went wrong'));
        }
    }

    public function edit(string $id): View
    {
        $slide = OrganizerFeatureSlide::query()->findOrFail($id);

        return view('backend.organizer_feature_slides.edit', compact('slide'));
    }

    public function update(UpdateOrganizerFeatureSlideRequest $request, string $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $slide = OrganizerFeatureSlide::query()->findOrFail($id);

            /** @var array<string, mixed> $data */
            $data = $request->safe()->except(['hero_image', 'hero_image_remote']);
            $data['hero_image'] = $this->resolveHeroImageValue($request, $slide->hero_image);
            $data['sort_order'] = (int) $request->input('sort_order', $slide->sort_order);
            $data['is_active'] = $request->boolean('is_active');

            $slide->update($data);

            DB::commit();

            return redirect()
                ->route('admin.organizer-feature-slides.index')
                ->with('success', __('Organizer slide updated successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('error')->error('OrganizerFeatureSlideController@update: '.$e->getMessage());

            return redirect()->back()->withInput()->with('error', __('Something went wrong'));
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            OrganizerFeatureSlide::query()->whereKey($id)->delete();

            return redirect()
                ->route('admin.organizer-feature-slides.index')
                ->with('success', __('Organizer slide deleted'));
        } catch (\Throwable $e) {
            Log::channel('error')->error('OrganizerFeatureSlideController@destroy: '.$e->getMessage());

            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    private function resolveHeroImageValue(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('hero_image')) {
            return $request->file('hero_image')->store('organizer_feature_slides', 'public');
        }

        $remote = trim((string) $request->input('hero_image_remote', ''));
        if ($remote !== '') {
            return $remote;
        }

        return $current;
    }
}
