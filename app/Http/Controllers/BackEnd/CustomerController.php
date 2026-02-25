<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Customer\StoreCustomerRequest;
use App\Http\Requests\Backend\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::get();

        return view('backend.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::create($request->safe()->all());

            if($request->hasFile('cover')){
               saveMedia($request , $customer);
            }

            DB::commit();
            session()->flash('success', 'Customer created successfully');
            return redirect()->route('admin.customers.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in CustomerController@store: ' . $e->getMessage());
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

            $customer = Customer::find($id);

            if(!$customer){
                session()->flash('error', 'Customer not found');
                return redirect()->route('admin.customers.index')->with('error', 'Customer not found');
            }

            DB::commit();
            return view('backend.customers.edit', compact('customer'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CustomerController@edit: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->route('admin.customers.index')->with('error', 'Something went wrong');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $customer = Customer::find($id);

            if(!$customer){
                session()->flash('error', 'Customer not found');
                return redirect()->route('admin.customers.index')->with('error', 'Customer not found');
            }

            $customer->update($request->safe()->all());

            if($request->hasFile('cover')){
                saveMedia($request , $customer);
            }

            DB::commit();
            session()->flash('success', 'Customer updated successfully');
            return redirect()->route('admin.customers.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CustomerController@update: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->route('admin.customers.index')->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $customer = Customer::find($id);

            if(!$customer){
                session()->flash('error', 'Customer not found');
                return redirect()->route('admin.customers.index')->with('error', 'Customer not found');
            }

            $customer->delete();

            DB::commit();
            session()->flash('success', 'Customer deleted successfully');
            return redirect()->route('admin.customers.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CustomerController@destroy: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->route('admin.customers.index')->with('error', 'Something went wrong');
        }
    }
}
