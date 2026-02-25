<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Slider\StoreSliderRequest;
use App\Http\Requests\Backend\Slider\UpdateSliderRequest;
use App\Models\Event;
use App\Models\Slider;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::get();    

        $sliders = $sliders->filter(function ($slider) {
            if ($slider->event) {
                return $slider->event->eventDates->first()->end_date > now();
            }
            return false;
        });

        return view('backend.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.   
     */
    public function create()
    {
        $events = Event::all();
        return view('backend.sliders.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {
        try {
            DB::beginTransaction();

            $slider = Slider::create($request->safe()->all());

            if (count($request->files) > 0) {
                saveMedia($request, $slider);
            }

            DB::commit();
            session()->flash('success', __('Slider created successfully'));
            return redirect()->route('admin.sliders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SliderController@store: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            DB::beginTransaction(); 

            $slider = Slider::find($id);

            if (!$slider) {
                session()->flash('error', __('Slider not found'));
                return redirect()->back();
            }

            DB::commit();
            return view('backend.sliders.show', compact('slider'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SliderController@show: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            DB::beginTransaction();

            $slider = Slider::with('event')->find($id);
            $events = Event::all();
            if (!$slider) {
                session()->flash('error', __('Slider not found'));
                return redirect()->back();
            }

            DB::commit();
            return view('backend.sliders.edit', compact('slider', 'events'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SliderController@edit: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $slider = Slider::find($id);

            if (!$slider) {
                session()->flash('error', __('Slider not found'));
                return redirect()->back();
            }

            $slider->update($request->safe()->all());

            if (count($request->files) > 0) {
                saveMedia($request, $slider);
            }
            DB::commit();
            session()->flash('success', __('Slider updated successfully'));
            return redirect()->route('admin.sliders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SliderController@update: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $slider = Slider::findOrFail($id);
            $slider->delete();

            DB::commit();
            session()->flash('success', __('Slider deleted successfully'));
            return redirect()->route('admin.sliders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SliderController@destroy: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }
}
