<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Http\Requests\Backend\Webinar\WebinarRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class WebinarController extends Controller
{
    public function index()
    {
        $webinars = Webinar::all();
        return view('backend.webinars.index', compact('webinars'));
    }


    /**
     * create new webinar
     */
    public function create()
    {
        return view('backend.webinars.create');
    }
    

    /**
     * store new webinar
     */
    public function store(WebinarRequest $request)
    {
        try {
            DB::beginTransaction();
            $webinar = Webinar::create($request->safe()
            ->merge([
                'slug' => Str::slug($request->webinar_name),
            ])
            ->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinar);
            }
            DB::commit();
            return redirect()->route('admin.webinars.index')->with('success', 'Webinar created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('Error in WebinarController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $webinar = Webinar::findOrFail($id);
        return view('backend.webinars.edit', compact('webinar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WebinarRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $webinar = Webinar::findOrFail($id);
            $webinar->update($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinar);
            }
            DB::commit();
            return redirect()->route('admin.webinars.index')->with('success', 'Webinar updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarController@update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $webinar = Webinar::findOrFail($id);
            $webinar->delete();
            DB::commit();
            return redirect()->route('admin.webinars.index')->with('success', 'Webinar deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }   
}
