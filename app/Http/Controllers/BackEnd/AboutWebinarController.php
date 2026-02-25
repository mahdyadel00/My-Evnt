<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Aboutwebinar;
use App\Models\Webinar;
use App\Http\Requests\Backend\AboutWebinar\RequestAboutWebinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AboutWebinarController extends Controller
{
    public function index()
    {
        $aboutwebinars = Aboutwebinar::all();
        return view('backend.aboutwebinars.index', compact('aboutwebinars'));
    }

    /**
     * create new about webinar
     */
    public function create()
    {
        $webinars = Webinar::all();
        return view('backend.aboutwebinars.create', compact('webinars'));
    }

    /**
     * store new about webinar
     */
    public function store(RequestAboutWebinar $request)
    {
        try {
            DB::beginTransaction();
            $aboutwebinar = Aboutwebinar::create($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $aboutwebinar);
            }
            DB::commit();
            return redirect()->route('admin.about_webinars.index')->with('success', 'About webinar created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AboutWebinarController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * edit about webinar
     */
    public function edit($id)
    {
        $webinars = Webinar::all();
        $aboutwebinar = Aboutwebinar::findOrFail($id);
        return view('backend.aboutwebinars.edit', compact('aboutwebinar', 'webinars'));
    }

    /**
     * update about webinar
     */
    public function update(RequestAboutWebinar $request, $id)
    {
        try {
            DB::beginTransaction();
            $aboutwebinar = Aboutwebinar::findOrFail($id);
            $aboutwebinar->update($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $aboutwebinar);
            }
            DB::commit();
            return redirect()->route('admin.about_webinars.index')->with('success', 'About webinar updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AboutWebinarController@update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * destroy about webinar
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $aboutwebinar = Aboutwebinar::findOrFail($id);
            $aboutwebinar->delete();
            DB::commit();
            return redirect()->route('admin.about_webinars.index')->with('success', 'About webinar deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AboutWebinarController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
