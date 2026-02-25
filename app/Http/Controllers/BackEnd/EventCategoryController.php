<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\eventCategory\StoreEventCategoryRequest;
use App\Http\Requests\Backend\eventCategory\UpdateEventCategoryRequest;
use App\Models\EventCategory;
use App\Repositories\EventCategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventCategoryController extends Controller
{
    protected $eventCategoryRepository;

    /**
     * EventCategoryController constructor.
     *
     * @param EventCategoryRepository $eventCategoryRepository
     */
    public function __construct(EventCategoryRepository $eventCategoryRepository)
    {
        $this->eventCategoryRepository = $eventCategoryRepository;
    }

    /**
     * Display a listing of the event categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $eventCategories = EventCategory::all();
        
        return view('backend.event_categories.index', compact('eventCategories'));
    }

    /**
     * Show the form for creating a new event category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $eventCategories = EventCategory::where('is_parent', true)->get();
        return view('backend.event_categories.create', compact('eventCategories'));
    }

    /**
     * Store a newly created event category in storage.
     *
     * @param StoreEventCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEventCategoryRequest $request)
    {

        try {
            DB::beginTransaction();

            $eventCategory = EventCategory::create($request->validated());

            if (count($request->files) > 0) {
                saveMedia($request, $eventCategory);
            }

            DB::commit();
            return redirect()->route('admin.event_categories.index')
                ->with('success', __('Event Category created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Display the specified event category.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        try {
            $eventCategory = $this->getEventCategoryOrFail($id);
            return view('backend.event_categories.show', compact('eventCategory'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->route('admin.event_categories.index')
                ->with('error', __('Event Category not found'));
        }
    }

    /**
     * Show the form for editing the specified event category.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        try {
            $eventCategory = $this->getEventCategoryOrFail($id);
            $parentCategories = EventCategory::where('is_parent', true)->get();
            return view('backend.event_categories.edit', compact('eventCategory', 'parentCategories'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->route('admin.event_categories.index')
                ->with('error', __('Event Category not found'));
        }
    }

    /**
     * Update the specified event category in storage.
     *
     * @param UpdateEventCategoryRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEventCategoryRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $eventCategory = $this->getEventCategoryOrFail($id);
            $eventCategory->update($request->validated());
            if (count($request->files) > 0) {
                saveMedia($request, $eventCategory);
            }
            DB::commit();
            return redirect()->route('admin.event_categories.index')
                ->with('success', __('Event Category updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('update', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Remove the specified event category from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $eventCategory = $this->getEventCategoryOrFail($id);
            $eventCategory->delete();

            DB::commit();
            return redirect()->route('admin.event_categories.index')
                ->with('success', __('Event Category deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Helper method to fetch an event category or throw exception.
     *
     * @param string $id
     * @return EventCategory
     * @throws \Exception
     */
    private function getEventCategoryOrFail(string $id): EventCategory
    {
        $eventCategory = $this->eventCategoryRepository->getById($id);

        if (!$eventCategory) {
            throw new \Exception("Event Category not found");
        }

        return $eventCategory;
    }

    /**
     * Helper method to log errors consistently.
     *
     * @param string $method
     * @param \Exception $e
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in EventCategoryController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}
