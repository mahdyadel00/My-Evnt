<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Packages\{StorePackageRequest, UpdatePackageRequest};
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::get();

        return view('backend.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePackageRequest $request)
    {
      try{
          DB::beginTransaction();

          Package::create($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Packages created successfully.');
            return redirect()->route('admin.packages.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in PackageController@store: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong.');
            return redirect()->route('admin.packages.index')->with('error', 'Something went wrong.');
      }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            DB::beginTransaction();

            $package = Package::find($id);

            if(!$package){
                session()->flash('error', 'Packages not found.');
                return redirect()->route('admin.packages.index')->with('error', 'Packages not found.');
            }

            DB::commit();
            return view('backend.packages.edit', compact('package'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in PackageController@edit: ' . $e->getMessage());
            return redirect()->route('admin.packages.index')->with('error', 'Something went wrong.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $package = Package::find($id);

            if(!$package){
                session()->flash('error', 'Packages not found.');
                return redirect()->route('admin.packages.index')->with('error', 'Packages not found.');
            }

            $package->update($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Packages updated successfully.');
            return redirect()->route('admin.packages.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in PackagesController@update: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong.');
            return redirect()->route('admin.packages.index')->with('error', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $package = Package::find($id);

            if(!$package){
                session()->flash('error', 'Packages not found.');
                return redirect()->route('admin.packages.index')->with('error', 'Packages not found.');
            }

            $package->delete();

            DB::commit();
            session()->flash('success', 'Packages deleted successfully.');
            return redirect()->route('admin.packages.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in PackagesController@destroy: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong.');
            return redirect()->route('admin.packages.index')->with('error', 'Something went wrong.');
        }
    }
}
