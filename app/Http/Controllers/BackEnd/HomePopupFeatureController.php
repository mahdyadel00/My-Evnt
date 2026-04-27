<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\HomePopupFeature\StoreHomePopupFeatureRequest;
use App\Http\Requests\Backend\HomePopupFeature\UpdateHomePopupFeatureRequest;
use App\Models\Event;
use App\Models\HomePopupFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomePopupFeatureController extends Controller
{
    public function index(): View
    {
        $items = HomePopupFeature::query()->with('event')->orderBy('sort_order')->orderByDesc('id')->get();
        $events = Event::query()->active()->orderBy('name')->get(['id', 'name', 'uuid']);

        return view('backend.home_popup_features.index', compact('items', 'events'));
    }

    public function create(): View
    {
        $events = Event::query()->active()->orderBy('name')->get(['id', 'name', 'uuid']);

        return view('backend.home_popup_features.create', compact('events'));
    }

    public function store(StoreHomePopupFeatureRequest $request): JsonResponse|RedirectResponse
    {
        $wantsJson = $request->ajax() || $request->wantsJson();

        try {
            DB::beginTransaction();
            $data = $request->safe()->except(['image']);
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('home_popup_features', 'public');
            }
            $item = HomePopupFeature::query()->create($data);
            $item->load('event');
            DB::commit();

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => __('Features saved successfully.'),
                    'row' => $this->popupRowPayload($item),
                ]);
            }

            session()->flash('success', __('Features saved successfully.'));

            return redirect()->route('admin.home-popup-features.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('error')->error('HomePopupFeatureController@store: '.$e->getMessage());

            if ($wantsJson) {
                $message = config('app.debug') ? $e->getMessage() : __('Something went wrong');

                return response()->json(['success' => false, 'message' => $message], 500);
            }
            session()->flash('error', __('Something went wrong'));

            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request, string $id): JsonResponse|View|RedirectResponse
    {
        $item = HomePopupFeature::query()->find($id);
        if (! $item) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('Not found')], 404);
            }
            session()->flash('error', __('Not found'));

            return redirect()->route('admin.home-popup-features.index');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'item' => $this->popupFormPayload($item),
            ]);
        }

        $events = Event::query()->active()->orderBy('name')->get(['id', 'name', 'uuid']);

        return view('backend.home_popup_features.edit', compact('item', 'events'));
    }

    public function update(UpdateHomePopupFeatureRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $wantsJson = $request->ajax() || $request->wantsJson();

        try {
            $item = HomePopupFeature::query()->find($id);
            if (! $item) {
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => __('Not found')], 404);
                }
                session()->flash('error', __('Not found'));

                return redirect()->route('admin.home-popup-features.index');
            }
            DB::beginTransaction();
            $data = $request->safe()->except(['image']);
            if ($request->hasFile('image')) {
                if ($item->image_path) {
                    Storage::disk('public')->delete($item->image_path);
                }
                $data['image_path'] = $request->file('image')->store('home_popup_features', 'public');
            }
            $item->update($data);
            $item->refresh()->load('event');
            DB::commit();

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => __('Features updated successfully.'),
                    'row' => $this->popupRowPayload($item),
                ]);
            }

            session()->flash('success', __('Features updated successfully.'));

            return redirect()->route('admin.home-popup-features.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('error')->error('HomePopupFeatureController@update: '.$e->getMessage());

            if ($wantsJson) {
                $message = config('app.debug') ? $e->getMessage() : __('Something went wrong');

                return response()->json(['success' => false, 'message' => $message], 500);
            }
            session()->flash('error', __('Something went wrong'));

            return redirect()->back()->withInput();
        }
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        $wantsJson = request()->ajax() || request()->wantsJson();

        try {
            $item = HomePopupFeature::query()->find($id);
            if (! $item) {
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => __('Not found')], 404);
                }
                session()->flash('error', __('Not found'));

                return redirect()->route('admin.home-popup-features.index');
            }
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $item->delete();

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => __('Deleted successfully.'),
                    'id' => (int) $id,
                ]);
            }

            session()->flash('success', __('Deleted successfully.'));

            return redirect()->route('admin.home-popup-features.index');
        } catch (\Throwable $e) {
            Log::channel('error')->error('HomePopupFeatureController@destroy: '.$e->getMessage());

            if ($wantsJson) {
                $message = config('app.debug') ? $e->getMessage() : __('Something went wrong');

                return response()->json(['success' => false, 'message' => $message], 500);
            }
            session()->flash('error', __('Something went wrong'));

            return redirect()->back();
        }
    }

    /**
     * @return array<string, int|string|null|bool>
     */
    private function popupFormPayload(HomePopupFeature $item): array
    {
        return [
            'id'                => $item->id,
            'event_id'              => $item->event_id,
            'title'                 => $item->title,
            'description'               => $item->description,
            'manual_location'               => $item->manual_location,
            'manual_datetime_label'                 => $item->manual_datetime_label,
            'cta_label'                 => $item->cta_label,
            'dismiss_label'                 => $item->dismiss_label,
            'show_action_buttons'               => $item->show_action_buttons,
            'sort_order'                => $item->sort_order,
            'is_active'                 => $item->is_active,
            'image_path'                => $item->image_path,
            'image_url'                 => $item->resolveManualUploadPublicUrl(),
        ];
    }

    /**
     * @return array<string, int|string>
     */
    private function popupRowPayload(HomePopupFeature $item): array
    {
        $item->loadMissing('event');

        return [
            'id'                => $item->id,
            'title'                 => \Illuminate\Support\Str::limit($item->resolveTitle(), 40),
            'event'                 => $item->event ? \Illuminate\Support\Str::limit($item->event->name, 30) : '—',
            'active'                => $item->is_active ? 'Yes' : 'No',
            'sort'              => (string) $item->sort_order,
        ];
    }
}
