<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Country\StoreCountryRequest;
use App\Http\Requests\Backend\Country\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        return view('backend.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        try {
            DB::beginTransaction();

            $country = Country::create($request->safe()->all());

            if ($request->hasFile('logo')) {
                $this->saveMedia($request, $country);
            }

            DB::commit();
            return redirect()->route('admin.countries.index')->with('success', 'Country created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $country = Country::findOrFail($id);
            return view('backend.countries.show', compact('country'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->back()->with('error', 'Country not found or something went wrong');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $country = Country::findOrFail($id);
            return view('backend.countries.edit', compact('country'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->back()->with('error', 'Country not found or something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $country = Country::findOrFail($id);
            $country->update($request->safe()->all());

            if ($request->hasFile('logo')) {
                $this->saveMedia($request, $country);
            }

            DB::commit();
            return redirect()->route('admin.countries.index')->with('success', 'Country updated successfully');
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

            $country = Country::findOrFail($id);
            $country->delete();

            DB::commit();
            return redirect()->route('admin.countries.index')->with('success', 'Country deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Helper method to save media for a country.
     */
    private function saveMedia(Request $request, Country $country)
    {
        // Assuming saveMedia is a global helper function; replace with your logic if different
        saveMedia($request, $country);
    }

    /**
     * Helper method to log errors consistently.
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in CountryController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}
