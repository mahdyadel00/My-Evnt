<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdFee\StoreAdFeeRequest;
use App\Http\Requests\Backend\AdFee\UpdateAdFeeRequest;
use App\Models\AdFee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ad_fees = AdFee::get();

        return view('backend.ad_fees.index', compact('ad_fees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.ad_fees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdFeeRequest $request)
    {
        try{
            DB::beginTransaction();

            AdFee::create($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Ad Fee created successfully');
            return redirect()->route('admin.ad_fees.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Ad Fee creation failed: ' . $e->getMessage());
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

            $ad_fee = AdFee::find($id);

            if(!$ad_fee){
                session()->flash('error', 'Ad Fee not found');
                return redirect()->back();
            }

            DB::commit();
            return view('backend.ad_fees.edit', compact('ad_fee'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Ad Fee edit failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdFeeRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $ad_fee = AdFee::find($id);

            if(!$ad_fee){
                session()->flash('error', 'Ad Fee not found');
                return redirect()->back();
            }

            $ad_fee->update($request->safe()->all());

            DB::commit();
            session()->flash('success', 'Ad Fee updated successfully');
            return redirect()->route('admin.ad_fees.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Ad Fee update failed: ' . $e->getMessage());
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

            $ad_fee = AdFee::find($id);

            if(!$ad_fee){
                session()->flash('error', 'Ad Fee not found');
                return redirect()->back();
            }

            $ad_fee->delete();

            DB::commit();
            session()->flash('success', 'Ad Fee deleted successfully');
            return redirect()->route('admin.ad_fees.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Ad Fee deletion failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
