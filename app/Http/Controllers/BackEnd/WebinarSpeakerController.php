<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WebinarSpeaker\WebinarSpeakerRequest;
use App\Models\Webinar;
use App\Models\Aboutwebinar;
use App\Models\WebinarSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebinarSpeakerController extends Controller
{
    public function index()
    {
        $webinarSpeakers = WebinarSpeaker::all();
        return view('backend.webinar_speakers.index', compact('webinarSpeakers'));
    }

    /**
     * create new webinar speaker
     */
    public function create()
    {
        $webinars = Webinar::all();
        $aboutwebinars = Aboutwebinar::all();
        return view('backend.webinar_speakers.create', compact('webinars', 'aboutwebinars'));
    }

    /**
     * store new webinar speaker
     */
    public function store(WebinarSpeakerRequest $request)
    {
        try {
            DB::beginTransaction();
            $webinarSpeaker = WebinarSpeaker::create($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinarSpeaker);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarSpeakerController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
        return redirect()->route('admin.webinar_speakers.index')->with('success', 'Webinar speaker created successfully');
    }

    /**
     * edit webinar speaker
     */
    public function edit(string $id)
    {
        $webinarSpeaker = WebinarSpeaker::findOrFail($id);
        $webinars = Webinar::all();
        $aboutwebinars = Aboutwebinar::all();
        return view('backend.webinar_speakers.edit', compact('webinarSpeaker', 'webinars', 'aboutwebinars'));
    }

    /**
     * update webinar speaker
     */
    public function update(WebinarSpeakerRequest $request, string $id)
    {
        $webinarSpeaker = WebinarSpeaker::findOrFail($id);
        try {
            DB::beginTransaction();
            $webinarSpeaker->update($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinarSpeaker);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarSpeakerController@update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
        return redirect()->route('admin.webinar_speakers.index')->with('success', 'Webinar speaker updated successfully');
    }

    /**
     * destroy webinar speaker
     */
    public function destroy(string $id)
    {
        $webinarSpeaker = WebinarSpeaker::findOrFail($id);
        try {
            DB::beginTransaction();
            $webinarSpeaker->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarSpeakerController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
        return redirect()->route('admin.webinar_speakers.index')->with('success', 'Webinar speaker deleted successfully');
    }

}
