<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Coupon\StoreCouponStore;
use App\Http\Requests\Backend\Coupon\UpdateCouponStore;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::with('event')->get();

        return view('backend.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::get();

        return view('backend.coupons.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponStore $request)
    {
        try{
            DB::beginTransaction();

            $coupon = Coupon::create($request->merge([
                  'code' => $request->code . '-' . strtoupper(substr(uniqid(), -4)),
            ])->all());

            DB::commit();
            session()->flash('success', 'Coupon created successfully');
            return redirect()->route('admin.coupons.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('coupon')->error('store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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

            $coupon = Coupon::with('event')->find($id); //with event

            if(!$coupon){
                session()->flash('error', 'Coupon not found');
                return redirect()->back();
            }

            DB::commit();
            $events = Event::get(); //with event
            return view('backend.coupons.edit', compact('coupon', 'events'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('coupon')->error('edit', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponStore $request, string $id)
    {
        try{
            DB::beginTransaction();

            $coupon = Coupon::with('event')->find($id); //with event

            if(!$coupon){
                session()->flash('error', 'Coupon not found');
                return redirect()->back();
            }

            $coupon->update($request->all());

            DB::commit();
            session()->flash('success', 'Coupon updated successfully');
            return redirect()->route('admin.coupons.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('coupon')->error('error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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

            $coupon = Coupon::with('event')->find($id); //with event    

            if(!$coupon){
                session()->flash('error', 'Coupon not found');
                return redirect()->back();
            }

            $coupon->delete();

            DB::commit();
            session()->flash('success', 'Coupon deleted successfully');
            return redirect()->route('admin.coupons.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('coupon')->error('error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
