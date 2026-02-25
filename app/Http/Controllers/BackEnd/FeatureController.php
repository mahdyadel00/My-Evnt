<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Feature\{StoreFeatureRequest, UpdateFeatureRequest};
use App\Models\{Feature, Package};
use Illuminate\Support\Facades\{DB, Log};

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = Feature::get();

        return view('backend.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::get();

        return view('backend.features.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request)
    {
        try{
            DB::beginTransaction();

            $feature = Feature::create($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Feature created successfully');
            return redirect()->route('admin.features.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in FeatureController@store: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            DB::beginTransaction();

            $feature = Feature::find($id);

            if(!$feature){
                session()->flash('error', 'Feature not found');
                return redirect()->back();
            }

            $packages = Package::get();

            DB::commit();
            return view('backend.features.edit', compact('feature', 'packages'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in FeatureController@edit: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $feature = Feature::find($id);

            if(!$feature){
                session()->flash('error', 'Pricing not found');
                return redirect()->back();
            }

            $feature->update($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Feature updated successfully');
            return redirect()->route('admin.features.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in FeatureController@update: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $feature = Feature::find($id);

            if(!$feature){
                session()->flash('error', 'Feature not found');
                return redirect()->back();
            }

            $feature->delete();

            DB::commit();
            session()->flash('success', 'Feature deleted successfully');
            return redirect()->route('admin.features.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in FeatureController@destroy: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
