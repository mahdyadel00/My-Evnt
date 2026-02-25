<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\City\CityRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::all();
        return view('backend.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        return view('backend.cities.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        try {
            DB::beginTransaction();

            $city = City::create($request->safe()->all());

            DB::commit();
            return redirect()->route('admin.cities.index')->with('success', 'City created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $city = City::findOrFail($id);
            $countries = Country::all();
            return view('backend.cities.edit', compact('city', 'countries'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->back()->with('error', 'City not found or something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $city = City::findOrFail($id);
            $city->update($request->safe()->all());

            DB::commit();
            return redirect()->route('admin.cities.index')->with('success', 'City updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('update', $e);
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

            $city = City::findOrFail($id);
            $city->delete();

            DB::commit();
            return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Helper method to log errors consistently.
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in CityController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}
