<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarCard;
use App\Http\Requests\Backend\WebinarCard\WebinarCardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebinarCardController extends Controller
{
    public function index()
    {
        $webinarCards = WebinarCard::get();
        return view('backend.webinar_cards.index', compact('webinarCards'));
    }

    /**
     * create new webinar card
     */
    public function create()
    {
        $webinars = Webinar::get();
        return view('backend.webinar_cards.create', compact('webinars'));
    }

    /**
     * store new webinar card
     */
    public function store(WebinarCardRequest $request)
    {
        try {
            DB::beginTransaction();
            $webinarCard = WebinarCard::create($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinarCard);
            }
            DB::commit();
            return redirect()->route('admin.webinar_cards.index')->with('success', 'Webinar card created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('Error in WebinarCardController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * edit webinar card
     */
    public function edit(string $id)
    {
        $webinars = Webinar::get();
        $webinarCard = WebinarCard::findOrFail($id);
        return view('backend.webinar_cards.edit', compact('webinarCard', 'webinars'));
    }

    /**
     * update webinar card
     */
    public function update(WebinarCardRequest $request, string $id)
    {
        $webinarCard = WebinarCard::findOrFail($id);
        try {
            DB::beginTransaction();
            $webinarCard->update($request->safe()->all());
            if ($request->hasFile('image')) {
                saveMedia($request, $webinarCard);
            }
            DB::commit();
            return redirect()->route('admin.webinar_cards.index')->with('success', 'Webinar card updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarCardController@update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * delete webinar card
     */
    public function destroy(string $id)
    {
        $webinarCard = WebinarCard::findOrFail($id);
        try {
            DB::beginTransaction();
            $webinarCard->delete();
            DB::commit();
        return redirect()->route('admin.webinar_cards.index')->with('success', 'Webinar card deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in WebinarCardController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}