<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Currency\StoreCurrencyRequest;
use App\Http\Requests\Backend\Currency\UpdateCurrencyRequest;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CurrencyController extends Controller
{

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = $this->currencyRepository->getAll();

        return view('backend.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request)
    {
        try{
            DB::beginTransaction();

            $currency = Currency::create($request->safe()->all());

            if (count($request->files) > 0) {
                saveMedia($request ,$currency);
            }

            DB::commit();
            session()->flash('success', __('Currency created successfully'));
            return redirect()->route('admin.currencies.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CurrencyController@store: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            DB::beginTransaction();

            $currency = $this->currencyRepository->getById($id);

            if(!$currency){
                session()->flash('error', __('Currency not found'));
                return redirect()->route('admin.currencies.index');
            }

            DB::commit();
            return view('backend.currencies.show', compact('currency'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CurrencyController@show: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->route('admin.currencies.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            DB::beginTransaction();

            $currency = $this->currencyRepository->getById($id);

            if(!$currency){
                session()->flash('error', __('Currency not found'));
                return redirect()->route('admin.currencies.index');
            }

            DB::commit();
            return view('backend.currencies.edit', compact('currency'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CurrencyController@edit: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->route('admin.currencies.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $currency = $this->currencyRepository->getById($id);

            if(!$currency){
                session()->flash('error', __('Currency not found'));
                return redirect()->route('admin.currencies.index');
            }

            $currency->update($request->safe()->all());

            if (count($request->files) > 0) {
                saveMedia($request ,$currency);
            }

            DB::commit();
            session()->flash('success', __('Currency updated successfully'));
            return redirect()->route('admin.currencies.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CurrencyController@update: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->route('admin.currencies.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $currency = $this->currencyRepository->getById($id);

            if(!$currency){
                session()->flash('error', __('Currency not found'));
                return redirect()->route('admin.currencies.index');
            }

            $currency->delete();

            DB::commit();
            session()->flash('success', __('Currency deleted successfully'));
            return redirect()->route('admin.currencies.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CurrencyController@destroy: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->route('admin.currencies.index');
        }
    }
}
