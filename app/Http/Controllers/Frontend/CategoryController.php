<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function category($id)
    {
        try{
            DB::beginTransaction();

            $event_category = EventCategory::where('id', $id)->first();

            if(!$event_category){
                session()->flash('error', 'Category not found');
                return redirect()->back()->with('error', 'Category not found');
            }

            DB::commit();
            return view('Frontend.layouts.category', compact('event_category'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in CategoryController@category: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
